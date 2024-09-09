<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Entity\Users; 
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ReservationsController extends AbstractController
{
    #[Route('/reservation/new/{userId}', name: 'reservation_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, $userId): Response
    {
        $legalAdvisor = $entityManager->getRepository(Users::class)->find($userId);
        if (!$legalAdvisor || $legalAdvisor->getSpecialite() === null) {
            throw $this->createNotFoundException('Avocat non trouvé ou non vérifié.');
        }

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $reservation = new Reservations();
        $reservation->setLegalAdvisor($legalAdvisor); // Associer l'avocat à la réservation
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setUser($user); // Le client faisant la réservation
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation réussie !');
            return $this->redirectToRoute('reservation_success');
        }

        return $this->render('reservation/new.html.twig', [
            'reservationForm' => $form->createView(),
        ]);
    }
}
