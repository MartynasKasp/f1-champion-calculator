<?php

namespace App\DataFixtures;

use App\Entity\RaceResult;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RaceResultFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /**
         * Season 2022, two races
         * Starting from Italy Grand Prix which was a 16th race of the season
         */
        $raceResult16 = (new RaceResult())
            ->setRace($this->getReference('race_2022_italy'))
            ->setSeason($this->getReference('season_2022'))
            ->setDriver($this->getReference('driver_1'))
            ->setPosition(1)
            ->setPoints(335);
        $manager->persist($raceResult16);
        // $this->addReference('race_result_2022_italy', $raceResult16);
        $raceResult17 = (new RaceResult())
            ->setRace($this->getReference('race_2022_singapore'))
            ->setSeason($this->getReference('season_2022'))
            ->setDriver($this->getReference('driver_1'))
            ->setPosition(7)
            ->setPoints(6);
        $manager->persist($raceResult17);

        $raceResult16 = (new RaceResult())
            ->setRace($this->getReference('race_2022_italy'))
            ->setSeason($this->getReference('season_2022'))
            ->setDriver($this->getReference('driver_11'))
            ->setPosition(6)
            ->setPoints(210);
        $manager->persist($raceResult16);
        // $this->addReference('race_result_2022_italy', $raceResult16);
        $raceResult17 = (new RaceResult())
            ->setRace($this->getReference('race_2022_singapore'))
            ->setSeason($this->getReference('season_2022'))
            ->setDriver($this->getReference('driver_11'))
            ->setPosition(1)
            ->setPoints(25);
        $manager->persist($raceResult17);

        $raceResult16 = (new RaceResult())
            ->setRace($this->getReference('race_2022_italy'))
            ->setSeason($this->getReference('season_2022'))
            ->setDriver($this->getReference('driver_16'))
            ->setPosition(2)
            ->setPoints(219);
        $manager->persist($raceResult16);
        // $this->addReference('race_result_2022_italy', $raceResult16);
        $raceResult17 = (new RaceResult())
            ->setRace($this->getReference('race_2022_singapore'))
            ->setSeason($this->getReference('season_2022'))
            ->setDriver($this->getReference('driver_16'))
            ->setPosition(2)
            ->setPoints(18);
        $manager->persist($raceResult17);

        $raceResult16 = (new RaceResult())
            ->setRace($this->getReference('race_2022_italy'))
            ->setSeason($this->getReference('season_2022'))
            ->setDriver($this->getReference('driver_63'))
            ->setPosition(3)
            ->setPoints(203);
        $manager->persist($raceResult16);
        // $this->addReference('race_result_2022_italy', $raceResult16);
        $raceResult17 = (new RaceResult())
            ->setRace($this->getReference('race_2022_singapore'))
            ->setSeason($this->getReference('season_2022'))
            ->setDriver($this->getReference('driver_63'))
            ->setPosition(14)
            ->setPoints(0);
        $manager->persist($raceResult17);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
            RaceFixtures::class,
            DriverFixtures::class,
        ];
    }
}
