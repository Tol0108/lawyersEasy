<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/users', name: 'user_list')]
    public function list(UsersRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/list.html.twig', ['users' => $users]);
    }

    #[Route('/users/specialite/{specialiteId}', name: 'users_by_specialite')]
    public function usersBySpecialite(UsersRepository $userRepository, $specialiteId): Response
    {
        $users = $userRepository->findBy(['specialite' => $specialiteId]);
        return $this->render('user/list_by_specialite.html.twig', [
            'users' => $users,
            'specialiteId' => $specialiteId
        ]);
    }
}
