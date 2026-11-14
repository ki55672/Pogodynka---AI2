<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Service\WeatherUtil;
use App\Entity\Forecast;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        WeatherUtil $weatherUtil,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false
    ): Response {
        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);
        if ($twig) {
            return $this->render(
                $format === 'csv' ? 'weather_api/index.csv.twig' : 'weather_api/index.json.twig',
                [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]
            );
        }
        if ($format === 'csv') {
            $rows = ['city,country,date,celsius,fahrenheit'];

            foreach ($measurements as $m) {
                $rows[] = sprintf(
                    '%s,%s,%s,%s,%s',
                    $city,
                    $country,
                    $m->getDate()?->format('Y-m-d') ?? '',
                    $m->getTemperatureCelsjus() ?? '',
                    $m->getFahrenheit() ?? ''
                );
            }

            return new Response(implode("\n", $rows), 200, [
                'Content-Type' => 'text/csv; charset=utf-8'
            ]);
        }
        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn(Forecast $m) => [
                'date' => $m->getDate()?->format('Y-m-d'),
                'celsius' => $m->getTemperatureCelsjus(),
                'fahrenheit' => $m->getFahrenheit(),
            ], $measurements),
        ]);
    }
}
