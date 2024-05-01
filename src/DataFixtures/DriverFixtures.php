<?php

namespace App\DataFixtures;

use App\Entity\Driver;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DriverFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $driver1 = new Driver();
        $driver1
            ->setNumber('1')
            ->setFullName('Max Verstappen');
        $manager->persist($driver1);
        $this->addReference('driver_1', $driver1);

        $driver2 = new Driver();
        $driver2
            ->setNumber('16')
            ->setFullName('Charles Leclerc');
        $manager->persist($driver2);
        $this->addReference('driver_16', $driver2);

        $driver3 = new Driver();
        $driver3
            ->setNumber('11')
            ->setFullName('Sergio Perez');
        $manager->persist($driver3);
        $this->addReference('driver_11', $driver3);

        $driver4 = new Driver();
        $driver4
            ->setNumber('63')
            ->setFullName('George Russell');
        $manager->persist($driver4);
        $this->addReference('driver_63', $driver4);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
