<?php

namespace App\Service;

use App\Entity\Driver;
use App\Entity\RaceResult;
use App\Entity\Season;
use App\Model\DTO\RaceResultDTO;
use Doctrine\ORM\EntityManagerInterface;

class RaceResultManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return RaceResultDTO[]
     */
    public function getDriversByStandingsForSeason(Season $season): array
    {
        /** @var \App\Repository\RaceResultRepository $raceResultRepo */
        $raceResultRepo = $this->entityManager->getRepository(RaceResult::class);
        /** @var \App\Repository\DriverRepository $driverRepo */
        $driverRepo = $this->entityManager->getRepository(Driver::class);

        return array_map(
            fn ($item) => new RaceResultDTO(
                driver: $driverRepo->find($item['driverId']),
                seasonPoints: $item['seasonPoints'],
            ),
            $raceResultRepo->getStandingsForSeason($season)
        );
    }
}
