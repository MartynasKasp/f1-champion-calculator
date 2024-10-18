<?php

namespace App\Repository;

use App\Entity\Race;
use App\Entity\RaceResult;
use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RaceResultRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceResult::class);
    }

    public function getStandingsForSeason(Season $season): array
    {
        return $this->createQueryBuilder('rr')
            ->select([
                'SUM(rr.points) as seasonPoints',
                'IDENTITY(rr.driver) as driverId',
            ])
            ->where('rr.season = :season')
            ->groupBy('driverId')
            ->addOrderBy('seasonPoints', 'DESC')
            ->setParameter('season', $season)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @return RaceResult[]
     */
    public function getResultsForRace(Race $race): array
    {
        return $this->createQueryBuilder('rr')
            ->where('rr.race = :race')
            ->setParameter('race', $race)
            ->orderBy('rr.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
