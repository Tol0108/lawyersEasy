<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OneOfYourCrudController extends AbstractController
{
    #[Route('/one/of/your/crud', name: 'app_one_of_your_crud')]
    public function index(): Response
    {
        return $this->render('one_of_your_crud/index.html.twig');   
    }
}
