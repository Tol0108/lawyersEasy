<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class SomeController extends AbstractController
{
    #[Route('/some', name: 'some')]
    public function index(): Response
    {
        return $this->render('some/index.html.twig', [
            'somecontroller' => 'SomeController',
        ]);
    }

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function someAction()
    {
        $user = $this->getUser();

    if (in_array('ROLE_CLIENT', $user->getRoles())) {
        // Logique spécifique au client
    } elseif (in_array('ROLE_AVOCAT', $user->getRoles())) {
        // Logique spécifique à l'avocat
    }
    }
}