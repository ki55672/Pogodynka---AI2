<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class MesurementTest extends TestCase
{
    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['1', '33.8'],
            ['2', '35.6'],
            ['10.5', '50.9'],
            ['20.5', '68.9'],
            ['-0.5', '31.1'],
            ['37.5', '99.5'],
        ];
    }

    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $forecast = new Forecast();
        $forecast->setTemperatureCelsjus((float) $celsius);

        $this->assertEquals((float) $expectedFahrenheit, $forecast->getFahrenheit());
    }
}