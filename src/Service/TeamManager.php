<?php

namespace App\Service;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

class TeamManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<\App\Entity\Team>
     */
    public function getAllTeams(): array
    {
        return $this->entityManager->getRepository(Team::class)
            ->findAll();
    }

    public function findTeamById(string $id): ?Team
    {
        return $this->entityManager->getRepository(Team::class)
            ->find($id);
    }
}
