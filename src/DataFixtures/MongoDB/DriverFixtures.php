<?php

namespace App\DataFixtures\MongoDB;

use App\Document\Driver;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;

class DriverFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $driver1 = new Driver();
        $driver1
            ->setNumber('1')
            ->setFullName('Max Verstappen')
            ->setPoints(341);
        $manager->persist($driver1);

        $driver2 = new Driver();
        $driver2
            ->setNumber('16')
            ->setFullName('Charles Leclerc')
            ->setPoints(237);
        $manager->persist($driver2);

        $driver3 = new Driver();
        $driver3
            ->setNumber('11')
            ->setFullName('Sergio Perez')
            ->setPoints(235);
        $manager->persist($driver3);

        $driver4 = new Driver();
        $driver4
            ->setNumber('63')
            ->setFullName('George Russell')
            ->setPoints(203);
        $manager->persist($driver4);

        $manager->flush();
    }
}
