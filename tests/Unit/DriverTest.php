<?php

namespace Tests\Unit;

use App\Entity\Driver;
use Tests\Support\UnitTester;

class DriverTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function testAddDriverPoints()
    {
        $driver = (new Driver())
            ->setNumber('1')
            ->setFullName('Nino Farina')
            ->setSeasonPoints(26);

        $driver->addSeasonPoints(4);

        $this->assertEquals(30, $driver->getSeasonPoints());
    }
}
