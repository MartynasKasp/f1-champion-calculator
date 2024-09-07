<?php

namespace App\Service;

use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class SeasonManager
{
    /** @var \App\Repository\SeasonRepository */
    protected EntityRepository $seasonRepository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->seasonRepository = $this->entityManager->getRepository(Season::class);
    }

    public function getCurrentSeason(): ?Season
    {
        return $this->seasonRepository->getCurrentSeason();
    }

    /**
     * @return Season[]
     */
    public function getAllSeasonsSorted(string $order = 'desc'): array
    {
        return $this->seasonRepository->getAllSeasonsSorted($order);
    }

    public function findSeasonById(string $id): ?Season
    {
        return $this->seasonRepository->find($id);
    }
}
