<?php

namespace Tests\Unit;

use App\Document\Driver;
use Tests\Support\UnitTester;

class DriverTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function testAddDriverPoints()
    {
        $driver = new Driver();
        $driver
            ->setNumber('1')
            ->setFullName('Nino Farina')
            ->setPoints(26);

        $driver->addPoints(4);

        $this->assertEquals(30, $driver->getPoints());
    }
}
