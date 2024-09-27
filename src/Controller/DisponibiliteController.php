<?php

namespace App\Controller;

use App\Entity\Disponibilite;
use App\Form\DisponibiliteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisponibiliteController extends AbstractController
{
    #[Route('/disponibilites/new', name: 'disponibilites_new', methods: ['GET', 'POST'])]
    public function newDisponibilite(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$this->isGranted('ROLE_User')) {
            throw $this->createAccessDeniedException('Accès réservé aux avocats.');
        }

        $disponibilite = new Disponibilite();
        $form = $this->createForm(DisponibiliteType::class, $disponibilite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $disponibilite->setAvocat($user); // Associer l'avocat connecté
            $entityManager->persist($disponibilite);
            $entityManager->flush();

            $this->addFlash('success', 'Disponibilité ajoutée avec succès.');
            return $this->redirectToRoute('disponibilite_list');
        }

        return $this->render('disponibilites/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/api/disponibilites', name: 'api_disponibilites', methods: ['GET'])]
    public function apiDisponibilites(EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();

        // Récupérer les disponibilités de l'avocat connecté
        $disponibilites = $entityManager->getRepository(Disponibilite::class)
            ->findBy(['avocat' => $user]);

        $events = [];

        foreach ($disponibilites as $disponibilite) {
            $events[] = [
                'title' => 'Disponible',
                'start' => $disponibilite->getStart()->format('Y-m-d\TH:i:s'),
                'end' => $disponibilite->getEnd()->format('Y-m-d\TH:i:s'),
            ];
        }

        return new JsonResponse($events);
    }

}
