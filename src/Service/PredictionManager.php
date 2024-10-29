<?php

namespace App\Service;

use App\Entity\Prediction;
use App\Entity\PredictionComparison;
use App\Model\DTO\PredictionDisplayDTO;
use App\Model\DTO\PredictionDisplayItemDTO;
use App\Util\NumberFormatterUtil;
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

    public function getFormattedPredictionForDisplay(Prediction $prediction): PredictionDisplayDTO
    {
        $predictionDisplay = new PredictionDisplayDTO();
        $predictionDisplay->leaderName = $prediction->getDriver()->getFullName();
        $predictionDisplay->grandPrix = $prediction->getRace()->getGrandPrix();
        $predictionDisplay->drivers[$prediction->getDriver()->getNumber()] = $prediction->getDriver()->getFullName();

        /** @var PredictionComparison $comparison */
        foreach ($prediction->getComparisons() as $comparison) {
            if (
                ! array_key_exists(($contender = $comparison->getContender())->getNumber(), $predictionDisplay->drivers)
            ) {
                $predictionDisplay->drivers[$contender->getNumber()] = $contender->getFullName();
            }

            $key = (string)$comparison->getLeaderPosition();
            if ($comparison->isLeaderFL()) {
                $key .= 'FL';
            }

            $leaderExistsForPosition = $this
                ->checkIfLeaderExistsForPredictionItem($key, $predictionDisplay, $prediction);
            if (! $leaderExistsForPosition) {
                $predictionDisplay->items[$key][] = $this->buildLeaderPredictionItem($prediction, $comparison);
            }

            $predictionDisplay->items[$key][] = $this->buildContenderPredictionItem($comparison);
        }

        return $predictionDisplay;
    }

    private function buildLeaderPredictionItem(
        Prediction $prediction,
        PredictionComparison $comparison
    ): PredictionDisplayItemDTO {
        $displayItem = new PredictionDisplayItemDTO();
        $displayItem->driverId = $prediction->getDriver()->getNumber();
        $displayItem->driverName = $prediction->getDriver()->getFullName();
        $displayItem->position = NumberFormatterUtil::formatOrdinal($comparison->getLeaderPosition());

        return $displayItem;
    }

    private function buildContenderPredictionItem(PredictionComparison $comparison): PredictionDisplayItemDTO
    {
        $displayItem = new PredictionDisplayItemDTO();
        $displayItem->driverId = $comparison->getContender()->getNumber();
        $displayItem->driverName = $comparison->getContender()->getFullName();
        $displayItem->position = -1 == $comparison->getHighestPosition()
            ? null
            : sprintf("%s or lower", NumberFormatterUtil::formatOrdinal($comparison->getHighestPosition()));

        return $displayItem;
    }

    private function checkIfLeaderExistsForPredictionItem(
        string $key,
        PredictionDisplayDTO $display,
        Prediction $prediction
    ): bool {
        return count(
            array_filter(
                $display->items[$key] ?? [],
                fn (PredictionDisplayItemDTO $item) => $prediction->getDriver()->getNumber() == $item->driverId
            )
        ) > 0;
    }
}
