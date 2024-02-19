<?php

namespace Tests\Unit;

use App\Document\Driver;
use App\Document\DriverStandings;
use App\Document\Season;
use Tests\Support\UnitTester;

class DriverTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function testAddDriverPoints()
    {
        $season = (new Season())
            ->setId('1950');

        $driver = (new Driver())
            ->setNumber('1')
            ->setFullName('Nino Farina');

        $standings = new DriverStandings();
        $standings
            ->setSeason($season)
            ->setDriver($driver)
            ->setPoints(26);

        $standings->addPoints(4);

        $this->assertEquals(30, $standings->getPoints());
    }
}
