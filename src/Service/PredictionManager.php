<?php

namespace App\Service;

use App\Entity\Prediction;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class PredictionManager
{
    /**
     * @var \App\Repository\PredictionRepository
     */
    protected EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        $this->repository = $entityManager->getRepository(Prediction::class);
    }

    // TODO filters
    public function getFilteredPredictions(): array
    {
        return $this->repository->getFilteredPredictions();
    }
}
