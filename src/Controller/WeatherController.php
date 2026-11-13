<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\WeatherUtil;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{city}', name: 'app_weather')]
    public function city(string $country, string $city, WeatherUtil $util): Response
    {
        $forecast = $util->getWeatherForCountryAndCity($country, $city);

        return $this->render('weather/city.html.twig', [
            'forecast' => $forecast,
        ]);
    }
}