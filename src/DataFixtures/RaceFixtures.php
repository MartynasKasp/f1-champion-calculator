<?php

namespace App\DataFixtures;

use App\Entity\Race;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RaceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /**
         * Season 2022, three races
         * Starting from Italy Grand Prix which was a 16th race of the season
         */
        $race16 = (new Race())
            ->setDate(new \DateTimeImmutable('2022-09-11'))
            ->setCompleted(true)
            ->setSeason($this->getReference('season_2022'))
            ->setGrandPrix('Italy');
        $manager->persist($race16);
        $this->addReference('race_2022_italy', $race16);

        $race17 = (new Race())
            ->setDate(new \DateTimeImmutable('2022-10-02'))
            ->setCompleted(true)
            ->setSeason($this->getReference('season_2022'))
            ->setGrandPrix('Singapore');
        $manager->persist($race17);
        $this->addReference('race_2022_singapore', $race17);

        $race18 = (new Race())
            ->setDate(new \DateTimeImmutable('2022-10-09'))
            ->setSeason($this->getReference('season_2022'))
            ->setGrandPrix('Japan');
        $manager->persist($race18);
        $this->addReference('race_2022_japan', $race18);

        /**
         * Season 2023, three races, one sprint
         * Starting from Singapore Grand Prix which was a 15th race of the season
         */
        $race15 = (new Race())
            ->setDate(new \DateTimeImmutable('2023-09-17'))
            ->setCompleted(true)
            ->setSeason($this->getReference('season_2023'))
            ->setGrandPrix('Singapore');
        $manager->persist($race15);
        $this->addReference('race_2023_singapore', $race15);

        $race16 = (new Race())
            ->setDate(new \DateTimeImmutable('2023-09-24'))
            ->setCompleted(true)
            ->setSeason($this->getReference('season_2023'))
            ->setGrandPrix('Japan');
        $manager->persist($race16);
        $this->addReference('race_2023_japan', $race16);

        $race17S = (new Race())
            ->setDate(new \DateTimeImmutable('2023-10-07'))
            ->setSprintRace(true)
            ->setSeason($this->getReference('season_2023'))
            ->setGrandPrix('Qatar');
        $manager->persist($race17S);
        $this->addReference('race_2023_qatar_sprint', $race17S);

        $race17 = (new Race())
            ->setDate(new \DateTimeImmutable('2023-10-08'))
            ->setSeason($this->getReference('season_2023'))
            ->setGrandPrix('Qatar');
        $manager->persist($race17);
        $this->addReference('race_2023_qatar', $race17);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
