<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnotherCrudController extends AbstractController
{
    #[Route('/example', name: 'example_route')]
    public function exampleMethod(): Response
    {
        return new Response('Example response');
    }
}

