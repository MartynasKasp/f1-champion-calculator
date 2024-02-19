<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class DriverStandingsRepository extends DocumentRepository
{
    public function getDriversByStandingsForSeason(string $season): array
    {
        return $this->createQueryBuilder()
            ->field('season.$id')
            ->equals($season)
            ->sort('points', -1)
            ->getQuery()
            ->execute()
            ->toArray();
    }
}
