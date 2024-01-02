<?php

namespace App\Controller;

use App\Service\OpenWeatherMapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    /**
     * @Route("/weather/{city}", name="weather")
     */
    public function weather($city, OpenWeatherMapService $openWeatherMapService)
    {
        $weatherData = $openWeatherMapService->getCurrentWeather($city);

        // Faites quelque chose avec les données météorologiques (par exemple, renvoyez-les dans une vue)

        return $this->render('weather/index.html.twig', [
            'weatherData' => $weatherData,
        ]);
    }

}
