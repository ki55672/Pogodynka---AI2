<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;

#[AsCommand(
    name: 'weather:location',
    description: 'Add a short description for your command',
)]
class WeatherLocationCommand{
      public function __construct(
        private LocationRepository $locationRepository,
        private WeatherUtil $weatherUtil
    ){}
    public function __invoke( 
    SymfonyStyle $io, 
    #[Argument('Location ID')] int $id, 
): int { 
    $location = $this->locationRepository->find($id); 
    $measurements = $this->weatherUtil->getWeatherForLocation($location); 
    $io->writeln(sprintf('Location: %s', $location->getCity())); 
    $io->table(['Date', 'Temperature'], array_map(fn($m) => [ 
        $m->getDate()->format('Y-m-d'), 
        $m->getTemperatureCelsjus(), 
    ], $measurements)); 
    return Command::SUCCESS; 
    }
} 
