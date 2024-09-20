<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Entity\Users; 
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;

class ReservationsController extends AbstractController
{
    private $logger;

    // Injectez le logger dans le constructeur
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/api/reservations', name: 'api_reservations', methods: ['GET'])]
    public function getReservations(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->logger->info('Fetching reservations');

        $userId = $request->query->get('userId');
        $legalAdvisor = $entityManager->getRepository(Users::class)->find($userId);

        if (!$legalAdvisor) {
            $this->logger->error('Avocat non trouvé.');
            return new JsonResponse(['error' => 'Avocat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $this->logger->info('Avocat trouvé, récupération des réservations.');
        $reservations = $entityManager->getRepository(Reservations::class)
            ->findBy(['legalAdvisor' => $legalAdvisor], null, 50);

        $reservationData = [];
        foreach ($reservations as $reservation) {
            $reservationData[] = [
                'id' => $reservation->getId(),
                'date' => $reservation->getDateReservation()->format('Y-m-d H:i:s'),
                'status' => $reservation->getStatus(),
            ];
        }

        $this->logger->info('Reservations fetched successfully.');
        return new JsonResponse($reservationData, Response::HTTP_OK);
    }

    #[Route('/api/reservations', name: 'api_create_reservation', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->logger->info('Création d\'une nouvelle réservation.');

        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['userId']) || !isset($data['date'])) {
            $this->logger->error('Données invalides.');
            return new JsonResponse(['error' => 'Données invalides.'], Response::HTTP_BAD_REQUEST);
        }

        $userId = $data['userId'];
        $date = $data['date'];

        $legalAdvisor = $entityManager->getRepository(Users::class)->find($userId);
        if (!$legalAdvisor) {
            $this->logger->error('Avocat non trouvé.');
            return new JsonResponse(['error' => 'Avocat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $user = $this->getUser();
        if (!$user) {
            $this->logger->error('Utilisateur non connecté.');
            return new JsonResponse(['error' => 'Utilisateur non connecté.'], Response::HTTP_UNAUTHORIZED);
        }

        $reservation = new Reservations();
        $reservation->setUser($user);
        $reservation->setLegalAdvisor($legalAdvisor);

        try {
            $reservation->setDateReservation(new \DateTime($date));
        } catch (\Exception $e) {
            $this->logger->error('Date invalide.');
            return new JsonResponse(['error' => 'Date invalide.'], Response::HTTP_BAD_REQUEST);
        }

        $this->logger->info('Réservation validée et en cours de sauvegarde.');
        $entityManager->persist($reservation);
        $entityManager->flush();

        $this->logger->info('Réservation créée avec succès.');
        return new JsonResponse([
            'status' => 'Réservation créée avec succès.',
            'reservationId' => $reservation->getId(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/reservations', name: 'reservation_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $this->logger->info('Fetching reservations for the logged-in user.');

        $user = $this->getUser();
        if (!$user) {
            $this->logger->error('Utilisateur non connecté.');
            return $this->redirectToRoute('login');
        }

        $reservations = $entityManager->getRepository(Reservations::class)->findBy(['user' => $user]);

        return $this->render('reservations/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/reservation/new/{userId}', name: 'reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $userId): Response
    {
        $this->logger->info('Création d\'une nouvelle réservation.');

        $legalAdvisor = $entityManager->getRepository(Users::class)->find($userId);
        if (!$legalAdvisor) {
            $this->logger->error('Avocat non trouvé.');
            throw $this->createNotFoundException('Avocat non trouvé.');
        }

        $user = $this->getUser();
        if (!$user) {
            $this->logger->error('Utilisateur non connecté.');
            return $this->redirectToRoute('login');
        }

        $reservation = new Reservations();
        $reservation->setLegalAdvisor($legalAdvisor);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->get('date_reservation')->getData();
            $timeSlot = $request->request->get('time-slot');
            if ($timeSlot) {
                $date->setTime((int)explode(':', $timeSlot)[0], (int)explode(':', $timeSlot)[1]);
            }

            $file = $form->get('documents')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('documents_directory'),
                        $newFilename
                    );
                    $reservation->setDocuments(new File($this->getParameter('documents_directory').'/'.$newFilename));
                } catch (FileException $e) {
                    $this->logger->error('Erreur lors du téléchargement du fichier.');
                    $this->addFlash('danger', 'Erreur lors du téléchargement du fichier.');
                }
            }

            $reservation->setUser($user);
            $reservation->setDateReservation($date);
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->logger->info('Réservation créée avec succès.');
            $this->addFlash('success', 'Réservation réussie !');
            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservations/new.html.twig', [
            'reservationForm' => $form->createView(),
        ]);
    }
}
