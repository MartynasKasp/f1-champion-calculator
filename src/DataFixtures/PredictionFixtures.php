<?php

namespace App\DataFixtures;

use App\Entity\Prediction;
use App\Entity\PredictionComparison;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PredictionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $prediction = (new Prediction())
            ->setRace($this->getReference('race_2023_qatar'))
            ->setDriver($this->getReference('driver_1'));
        $manager->persist($prediction);

        $comparison = (new PredictionComparison())
            ->setLeaderPosition(1)
            ->setLeaderFL(false)
            ->setContender($this->getReference('driver_16'))
            ->setHighestPosition(3)
            ->setWithoutFL(true);
        $manager->persist($comparison);
        $prediction->addComparison($comparison);

        $comparison = (new PredictionComparison())
            ->setLeaderPosition(1)
            ->setLeaderFL(true)
            ->setContender($this->getReference('driver_16'))
            ->setHighestPosition(2)
            ->setWithoutFL(true);
        $manager->persist($comparison);
        $prediction->addComparison($comparison);

        $comparison = (new PredictionComparison())
            ->setLeaderPosition(1)
            ->setLeaderFL(true)
            ->setContender($this->getReference('driver_11'))
            ->setHighestPosition(3)
            ->setWithoutFL(true);
        $manager->persist($comparison);
        $prediction->addComparison($comparison);

        $comparison = (new PredictionComparison())
            ->setLeaderPosition(1)
            ->setLeaderFL(false)
            ->setContender($this->getReference('driver_11'))
            ->setHighestPosition(4)
            ->setWithoutFL(true);
        $manager->persist($comparison);
        $prediction->addComparison($comparison);

        $comparison = (new PredictionComparison())
            ->setLeaderPosition(2)
            ->setLeaderFL(false)
            ->setContender($this->getReference('driver_16'))
            ->setHighestPosition(2)
            ->setWithoutFL(true);
        $manager->persist($comparison);
        $prediction->addComparison($comparison);

        $comparison = (new PredictionComparison())
            ->setLeaderPosition(2)
            ->setLeaderFL(false)
            ->setContender($this->getReference('driver_11'))
            ->setHighestPosition(3)
            ->setWithoutFL(true);
        $manager->persist($comparison);
        $prediction->addComparison($comparison);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [RaceFixtures::class, DriverFixtures::class];
    }
}
