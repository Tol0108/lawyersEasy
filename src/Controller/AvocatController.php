<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AvocatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvocatController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/avocat', name: 'avocat_List')]
    public function index(): Response
    {
        // Supposons que les avocats sont des utilisateurs avec le rôle 'ROLE_AVOCAT'
        $avocats = $this->entityManager->getRepository(Users::class)->findBy(['roles' => ['ROLE_AVOCAT']]);
        return $this->render('avocat/index.html.twig', [
            'avocats' => $avocats,
        ]);
    }

    #[Route('/avocat/new', name: 'avocat_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $avocat = new Users(); 
        $form = $this->createForm(AvocatType::class, $avocat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$avocat->setRoles(['ROLE_AVOCAT']); 
            $this->entityManager->persist($avocat);
            $this->entityManager->flush();

            return $this->redirectToRoute('avocat_index');
        }

        return $this->render('avocat/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
