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
        private string $firstSeason = '2022'
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

    public function getAvailableSeasons(): array
    {
        $startDate = new \DateTimeImmutable($this->firstSeason . '-01-01');
        $interval = \DateInterval::createFromDateString('1 year');

        $availableYears = [];
        foreach (new \DatePeriod($startDate, $interval, new \DateTimeImmutable()) as $year) {
            array_unshift($availableYears, $year->format('Y'));
        }

        return $availableYears;
    }
}
