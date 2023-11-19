<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class DriverRepository extends DocumentRepository
{
    public function getDriversByStandings(): array
    {
        return $this->createQueryBuilder()
            ->sort('points', -1)
            ->getQuery()
            ->execute()
            ->toArray();
    }
}
