<?php

namespace App\Repository;

use App\Entity\RaceResult;
use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RaceResultRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceResult::class);
    }

    public function getStandingsForSeason(Season $season)
    {
        // TODO aggregate points
        return $this->createQueryBuilder('r')
            ->where('season = :season')
            ->setParameter('season', $season)
            ->addOrderBy('points', 'DESC')
            ->getQuery()
            ->execute();
    }
}
