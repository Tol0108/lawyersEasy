<?php

namespace App\Controller;

use App\Service\CloudConvertService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConvertController extends AbstractController
{
    /**
     * @Route("/convert", name="convert")
     */
    public function convert(Request $request, CloudConvertService $cloudConvertService)
    {
        // Remplacez ces valeurs par celles de votre choix
        $inputFormat = 'pdf';
        $outputFormat = 'jpg';
        $inputFile = '/chemin/vers/votre/fichier.pdf';

        $result = $cloudConvertService->convert($inputFormat, $outputFormat, $inputFile);

        return new Response($result);
    }
}
