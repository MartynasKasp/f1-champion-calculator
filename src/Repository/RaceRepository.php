<?php

namespace App\Repository;

use App\Entity\Race;
use App\Entity\Season;
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
}
