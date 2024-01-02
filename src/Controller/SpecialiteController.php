<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Specialite;

class SpecialiteController extends AbstractController
{
    #[Route('/specialite', name: 'specialite')]
    public function index(EntityManagerInterface $entityManager)
    {
        $specialites = $entityManager->getRepository(Specialite::class)->findAll();
        return $this->render('specialite', [
            'specialite' => $specialites,
        ]);
    }
}