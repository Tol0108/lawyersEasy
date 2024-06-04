<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($hashedPassword);
            $user->setIsActive(true);

         // Définir des rôles en fonction des données du formulaire
         if ($user->getSpecialite()) {
            $user->setRoles(['ROLE_LAWYER']);
            $user->setIsVerified(false); // Les avocats doivent être vérifiés après l'inscription
        } else {
            $user->setRoles(['ROLE_USER']);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');

        // Rediriger en fonction du rôle
        if ($user->getRoles()[0] == 'ROLE_LAWYER') {
            return $this->redirectToRoute('lawyer_profile_complete');
        }
        return $this->redirectToRoute('user_dashboard');
    } else if ($form->isSubmitted() && !$form->isValid()) {
        $this->addFlash('error', 'Des erreurs sont présentes dans le formulaire.');
    }

    return $this->render('registration/register.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}
}
