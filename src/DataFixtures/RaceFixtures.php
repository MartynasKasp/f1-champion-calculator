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

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
