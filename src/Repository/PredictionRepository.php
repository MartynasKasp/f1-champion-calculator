<?php

namespace App\Repository;

use App\Entity\Prediction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class PredictionRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, Prediction::class);
    }

    public function findPredictionForRace(string $raceId): ?Prediction
    {
        return $this->createQueryBuilder('p')
            ->where('p.race = :RACE')
            ->setParameter('RACE', $raceId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
