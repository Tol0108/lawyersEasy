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

        $header = $request->headers->get('Stripe-Signature');
        $payload = $request->getContent();

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $header, 'your_stripe_webhook_secret'
            );
        } catch(\Exception $e) {
            // La signature ne correspond pas, la requête pourrait être falsifiée
            return new Response('Invalid signature', 403);
        }
        
        $payload = $request->getContent();
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return new Response('Invalid payload', 400);
        }

        // Gérer l'événement
        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;

            // Logique pour marquer le rendez-vous comme payé
        }

        return new Response('Webhook handled', 200);
    }
}
