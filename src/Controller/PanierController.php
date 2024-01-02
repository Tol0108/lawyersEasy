<?php

namespace App\Controller;

use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PanierController extends AbstractController
{
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            // Gérer l'absence d'utilisateur connecté
        }

        $panier = $user->getPanier(); // Supposons que la méthode getPanier() existe dans l'entité Users

        // Faire quelque chose avec le panier
        // ...

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
        ]);
    }
}
