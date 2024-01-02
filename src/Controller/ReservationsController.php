<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Entity\Users; 
use App\Entity\Avocat; 
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ReservationsController extends AbstractController
{
    #[Route('/reservation/new/{avocatId}', name: 'reservation_new')] // Ajout de {avocatId} dans la route
    public function new(Request $request, EntityManagerInterface $entityManager, $avocatId): Response // Ajout de $avocatId en tant que paramètre
    {
        $avocat = $entityManager->getRepository(Avocat::class)->find($avocatId);
        if (!$avocat) {
            throw $this->createNotFoundException('Avocat non trouvé.');
        }

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $reservation = new Reservations();
        $reservation->setAvocat($avocat); // Associer l'avocat à la réservation
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setUser($user);
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
