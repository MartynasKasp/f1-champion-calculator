<?php

namespace App\Service;

use App\Entity\Circuit;
use Doctrine\ORM\EntityManagerInterface;

class CircuitManager
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @return Circuit[]
     */
    public function getAllCircuits(): array
    {
        return $this->entityManager->getRepository(Circuit::class)
            ->findAll();
    }

    public function findCircuitById(string $id): ?Circuit
    {
        return $this->entityManager->getRepository(Circuit::class)
            ->find($id);
    }

    public function findCircuit(string $circuit): ?Circuit
    {
        return $this->entityManager->getRepository(Circuit::class)
            ->findOneBy(['circuit' => $circuit]);
    }
}
