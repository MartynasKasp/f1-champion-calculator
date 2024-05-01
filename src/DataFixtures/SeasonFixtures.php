<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $season2022 = new Season();
        $season2022
            ->setId('2022')
            ->setStartsAt(new \DateTime('2022-03-20'))
            ->setEndsAt(new \DateTime('2022-11-20'))
            ->setRaces(22)
            ->setSprints(3)
            ->setCompletedRaces(17)
            ->setCompletedSprints(2);
        $manager->persist($season2022);
        $this->addReference('season_2022', $season2022);

        $season2023 = new Season();
        $season2023
            ->setId('2023')
            ->setStartsAt(new \DateTime('2022-03-05'))
            ->setEndsAt(new \DateTime('2022-11-26'))
            ->setRaces(23)
            ->setSprints(6)
            ->setCompletedRaces(17)
            ->setCompletedSprints(3);
        $manager->persist($season2023);
        $this->addReference('season_2023', $season2023);

        $manager->flush();
    }
}
