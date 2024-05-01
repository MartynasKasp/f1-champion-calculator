<?php

namespace App\Repository;

use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class SeasonRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, Season::class);
    }

    public function getCurrentSeason(): ?\App\Entity\Season
    {
        $date = new \DateTimeImmutable();

        return $this->createQueryBuilder('s')
            ->where('id = :year')
            ->setParameter('year', $date->format('Y'))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findSeasonInPeriod(\DateTimeImmutable $date): ?\App\Entity\Season
    {
        return $this->createQueryBuilder('s')
            ->where('startsAt >= :date')
            ->andWhere('endsAt <= :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
