<?php

namespace App\Service;

use App\Entity\RaceResult;
use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;

class RaceResultManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return \App\Entity\Driver[]
     */
    public function getDriversByStandingsForSeason(Season $season): array
    {
        /** @var \App\Repository\RaceResultRepository $raceResultRepo */
        $raceResultRepo = $this->entityManager->getRepository(RaceResult::class);
        $standings = $raceResultRepo->getStandingsForSeason($season);

        return array_map(
            fn (RaceResult $standing) => $standing->getDriver(),
            $standings
        );
    }
}
