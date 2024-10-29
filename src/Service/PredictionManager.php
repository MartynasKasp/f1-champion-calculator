<?php

namespace App\Service;

use App\Entity\Prediction;
use Doctrine\ORM\EntityManagerInterface;

class PredictionManager
{
    /**
     * @var \App\Repository\PredictionRepository
     */
    protected $repository;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        /** @var \App\Repository\PredictionRepository $repo */
        $repo = $entityManager->getRepository(Prediction::class);
        $this->repository = $repo;
    }

    // TODO filters
    public function getFilteredPredictions(): array
    {
        return $this->repository->getFilteredPredictions();
    }

    public function findPredictionById(string $id): ?Prediction
    {
        return $this->repository->find($id);
    }
}
