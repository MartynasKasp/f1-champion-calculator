<?php

namespace App\Repository;

use App\Entity\Driver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DriverRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, Driver::class);
    }

    public function getDriverByFullName(string $fullName): ?Driver
    {
        $qb = $this->createQueryBuilder('d')
            ->where('d.fullName = :fullName')
            ->setParameter('fullName', $fullName);

        return $qb
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getDriverByNumber(string $number): ?Driver
    {
        $qb = $this->createQueryBuilder('d')
            ->where('d.number = :number')
            ->setParameter('number', $number);

        return $qb
            ->getQuery()
            ->getOneOrNullResult();
    }
}
