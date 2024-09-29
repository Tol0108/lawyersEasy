<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PaiementController extends AbstractController
{
    #[Route('/create-checkout-session', name: 'create_checkout_session')]
    public function checkout(): Response
    {
        Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Consultation',
                    ],
                    'unit_amount' => 5000, // 50 euros
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], true),
            'cancel_url' => $this->generateUrl('cancel_url', [], true),
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/stripe/webhook', name: 'stripe_webhook', methods: ['POST'])]
    public function stripeWebhook(Request $request): Response
    {
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        $endpoint_secret = $this->getParameter('stripe_webhook_secret');
        $payload = $request->getContent();
        $sig_header = $request->headers->get('stripe-signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return new Response('Invalid payload', 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new Response('Invalid signature', 400);
        }

        // Handling the event
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;

            // Logique pour marquer le rendez-vous comme payé
            // Par exemple : update de la base de données pour confirmer le paiement
        }

        return new Response('Webhook handled', 200);
    }
}
