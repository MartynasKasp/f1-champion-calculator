<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class SeasonRepository extends DocumentRepository
{
    public function getCurrentSeason(): \App\Document\Season
    {
        $date = new \DateTime();

        $qb = $this->createQueryBuilder();
        return $qb
            ->field('startsAt')
            ->lte($date)
            ->field('endsAt')
            ->gte($date)
            ->getQuery()
            ->getSingleResult();
    }

    public function findSeasonInPeriod(\DateTime $date): ?\App\Document\Season
    {
        $qb = $this->createQueryBuilder();
        return $qb
            ->field('startsAt')
            ->lte($date)
            ->field('endsAt')
            ->gte($date)
            ->getQuery()
            ->getSingleResult();
    }
}
