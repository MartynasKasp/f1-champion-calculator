<?php

namespace App\Service;

use App\Entity\Race;
use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class RaceManager
{
    /** @var \App\Repository\RaceRepository */
    protected EntityRepository $raceRepository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->raceRepository = $this->entityManager->getRepository(Race::class);
    }

    public function getNextRaceForSeason(?Season $season = null, bool $sprintOnly = false)
    {
        /** @var \App\Repository\SeasonRepository $seasonRepository */
        $seasonRepository = $this->entityManager->getRepository(Season::class);

        if (null === $season) {
            $season = $seasonRepository->findSeasonInPeriod(new \DateTimeImmutable('2023-05-01'));
        }

        return $this->raceRepository->getNextRaceForSeason($season, $sprintOnly);
    }
}
