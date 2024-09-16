<?php

namespace App\Service;

use App\Entity\Race;
use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class RaceManager
{
    const TYPE_RACE = 'Race';
    const TYPE_SPRINT = 'Sprint';

    /** @var \App\Repository\RaceRepository */
    protected EntityRepository $raceRepository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->raceRepository = $this->entityManager->getRepository(Race::class);
    }

    public static function getAvailableTypes(): array
    {
        return [self::TYPE_RACE, self::TYPE_SPRINT];
    }

    public function getNextRaceForSeason(?Season $season = null, bool $sprintOnly = false)
    {
        /** @var \App\Repository\SeasonRepository $seasonRepository */
        $seasonRepository = $this->entityManager->getRepository(Season::class);

        if (null === $season) {
            $season = $seasonRepository->findSeasonInPeriod(new \DateTimeImmutable());
        }

        return $this->raceRepository->getNextRaceForSeason($season, $sprintOnly);
    }

    /**
     * @return Race[]
     */
    public function getFilteredRaces(
        array $filters = [],
        int $limit = 30,
        int $offset = 0,
    ): array {
        return $this->raceRepository->getFilteredRaces($filters, $limit, $offset);
    }

    public function findRaceById(string $id): ?Race
    {
        return $this->raceRepository->find($id);
    }
}
