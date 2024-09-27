<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Entity\Users;
use App\Entity\Avocat; 
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class ReservationsController extends AbstractController
{
    private $logger;
    private $entityManager;
    private $mailer;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    #[Route('/api/reservations', name: 'api_reservations', methods: ['GET'])]
    public function getReservations(Request $request, EntityManagerInterface $entityManager): Response
    {

        $userId = $request->query->get('userId');
        $legalAdvisor = $entityManager->getRepository(Avocat::class)->find($userId);

        if (!$legalAdvisor) {
            return new JsonResponse(['error' => 'Avocat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $reservations = $entityManager->getRepository(Reservations::class)
            ->findBy(['avocat' => $legalAdvisor], null, 50);  // Utiliser 'avocat'

        $reservationData = [];
        foreach ($reservations as $reservation) {
            $reservationData[] = [
                'id' => $reservation->getId(),
                'date' => $reservation->getDateReservation()->format('Y-m-d H:i:s'),
                'status' => $reservation->getStatus(),
            ];
        }

        return new JsonResponse($reservationData, Response::HTTP_OK);
    }

    #[Route('/api/reservations', name: 'api_create_reservation', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['userId']) || !isset($data['date'])) {
            return new JsonResponse(['error' => 'Données invalides.'], Response::HTTP_BAD_REQUEST);
        }

        $userId = $data['userId'];
        $date = $data['date'];

        $legalAdvisor = $entityManager->getRepository(Avocat::class)->find($userId); // Rechercher un Avocat
        if (!$legalAdvisor) {
            return new JsonResponse(['error' => 'Avocat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non connecté.'], Response::HTTP_UNAUTHORIZED);
        }

        $reservation = new Reservations();
        $reservation->setUser($user);
        $reservation->setAvocat($legalAdvisor);  // Assigner l'avocat à la réservation

        try {
            $reservation->setDateReservation(new \DateTime($date));
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Date invalide.'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($reservation);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'Réservation créée avec succès.',
            'reservationId' => $reservation->getId(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/reservations', name: 'reservation_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $reservations = $entityManager->getRepository(Reservations::class)->findBy(['user' => $user]);

        return $this->render('reservations/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/reservation/new/{userId}', name: 'reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $userId, EntityManagerInterface $entityManager): Response
    {
        $legalAdvisor = $entityManager->getRepository(Avocat::class)->find($userId);
        if (!$legalAdvisor) {
            throw $this->createNotFoundException('Avocat non trouvé.');
        }

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $reservation = new Reservations();
        $reservation->setAvocat($legalAdvisor);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer la date de la réservation demandée
            $date = $form->get('date_reservation')->getData();

            // Vérifier si le créneau est déjà réservé pour cet avocat
            $existingReservation = $entityManager->getRepository(Reservations::class)->findOneBy([
                'avocat' => $legalAdvisor,
                'date_reservation' => $date,
            ]);

            if ($existingReservation) {
                // Si le créneau est déjà réservé
                $this->addFlash('danger', 'Ce créneau horaire est déjà réservé.');
                return $this->redirectToRoute('reservation_new', ['userId' => $userId]);
            }

            // Si le créneau est disponible, on enregistre la réservation
            $reservation->setUser($user);
            $reservation->setDateReservation($date);
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre réservation a été confirmée.');
            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservations/new.html.twig', [
            'reservationForm' => $form->createView(),
            'legalAdvisor' => $legalAdvisor
        ]);
    }

    // Fonction pour envoyer la notification email à l'avocat
    private function sendReservationNotification(Avocat $legalAdvisor, Reservations $reservation) // Changement de type ici
    {
        $email = (new Email())
            ->from('no-reply@votresite.com')
            ->to($legalAdvisor->getEmail())
            ->subject('Nouvelle réservation confirmée')
            ->text('Vous avez une nouvelle réservation pour le ' . $reservation->getDateReservation()->format('Y-m-d H:i') . '. Merci de vous connecter pour plus de détails.');

        $this->mailer->send($email);
    }
}
