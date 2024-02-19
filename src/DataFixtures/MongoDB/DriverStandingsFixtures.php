<?php

namespace App\DataFixtures\MongoDB;

use App\Document\DriverStandings;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DriverStandingsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $season2022 = $this->getReference('season_2022');

        $standings1 = new DriverStandings();
        $standings1
            ->setSeason($season2022)
            ->setDriver($this->getReference('driver_1'))
            ->setPoints(341);
        $manager->persist($standings1);

        $standings2 = new DriverStandings();
        $standings2
            ->setSeason($season2022)
            ->setDriver($this->getReference('driver_16'))
            ->setPoints(237);
        $manager->persist($standings2);

        $standings3 = new DriverStandings();
        $standings3
            ->setSeason($season2022)
            ->setDriver($this->getReference('driver_11'))
            ->setPoints(235);
        $manager->persist($standings3);

        $standings4 = new DriverStandings();
        $standings4
            ->setSeason($season2022)
            ->setDriver($this->getReference('driver_63'))
            ->setPoints(203);
        $manager->persist($standings4);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [DriverFixtures::class];
    }
}
