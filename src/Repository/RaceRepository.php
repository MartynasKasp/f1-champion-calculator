<?php

namespace App\Repository;

use App\Entity\Race;
use App\Entity\Season;
use App\Service\RaceManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RaceRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, Race::class);
    }

    public function getNextRaceForSeason(Season $season, bool $sprintOnly = false): ?Race
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.completed = FALSE')
            ->andWhere('r.season = :season');

        if ($sprintOnly) {
            $qb->andWhere('r.sprintRace = TRUE');
        }

        return $qb
            ->orderBy('r.date', 'ASC')
            ->setParameter('season', $season)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Race[]
     */
    public function getFilteredRaces(
        array $filters = [],
        int $limit = 10,
        int $offset = 0,
    ): array {
        $qb = $this->createQueryBuilder('r');

        if (isset($filters['year']) && !empty($filters['year'])) {
            $qb
                ->where('r.season = :season')
                ->setParameter('season', $filters['year']);
        }

        if (isset($filters['raceType']) && !empty($filters['raceType'])) {
            if (RaceManager::TYPE_RACE === $filters['raceType']) {
                $qb->andWhere('r.sprintRace = FALSE');
            } elseif (RaceManager::TYPE_SPRINT === $filters['raceType']) {
                $qb->andWhere('r.sprintRace = TRUE');
            }
        }

        return $qb
            ->orderBy('r.date', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}
