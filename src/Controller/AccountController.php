<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Reservations;
use App\Form\ReservationType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Stripe\StripeClient;

class AccountController extends AbstractController
{
    private $entityManager;
    private $stripeClient;

    public function __construct(EntityManagerInterface $entityManager, string $stripeSecretKey = null)
    {
        $this->entityManager = $entityManager;
        if ($stripeSecretKey) {
            $this->stripeClient = new StripeClient($stripeSecretKey);
        }
    }

    #[Route("/recap", name: "recap")]
    public function index(Request $request, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $reservations = $this->entityManager->getRepository(Reservations::class)->findBy(['user' => $user]);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($password = $form->get('plainPassword')->getData()) {
  //              $user->setPassword($passwordHasher->hashPassword($user, $password));
            }
            $this->entityManager->flush();
            $this->addFlash('success', 'Vos informations ont été mises à jour.');
        }

        return $this->render('moncompte/recap.html.twig', [
            'user' => $user,
            'reservations' => $reservations,
            'form' => $form->createView(),
        ]);
    }
}
