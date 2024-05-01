<?php

namespace App\Service;

use App\Entity\Driver;
use App\Entity\Prediction;
use App\Entity\PredictionComparison;
use App\Entity\Season;
use App\Trait\LoggerInjector;
use Doctrine\ORM\EntityManagerInterface;

class CalculatorManager
{
    use LoggerInjector;

    const POINTS_RACE_P1 = 25;
    const POINTS_RACE_P10 = 1;
    const POINTS_RACE_FASTEST = 1;
    const POINTS_RACE_MAX = self::POINTS_RACE_P1 + self::POINTS_RACE_FASTEST;

    const POINTS_SPRINT_P1 = 8;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private RaceResultManager $raceResultManager,
    ) {
    }

    public static function getRacePointsForFinish(): array
    {
        return [
            '1' => self::POINTS_RACE_P1,
            '2' => 18,
            '3' => 15,
            '4' => 12,
            '5' => 10,
            '6' => 8,
            '7' => 6,
            '8' => 4,
            '9' => 2,
            '10' => self::POINTS_RACE_P10,
        ];
    }

    public static function getSprintPointsForFinish(): array
    {
        return [
            '1' => self::POINTS_SPRINT_P1,
            '2' => 7,
            '3' => 6,
            '4' => 5,
            '5' => 4,
            '6' => 3,
            '7' => 2,
            '8' => 1,
        ];
    }

    public function calculateAvailablePoints(int $racesRemaining, int $sprintsRemaining): int
    {
        return ($racesRemaining * (self::POINTS_RACE_P1 + self::POINTS_RACE_FASTEST))
        + ($sprintsRemaining * self::POINTS_SPRINT_P1);
    }

    public function calculate(): void
    {
        // TODO
        /** @var \App\Repository\SeasonRepository $seasonRepository */
        $seasonRepository = $this->entityManager->getRepository(Season::class);
        $currentSeason = $seasonRepository->getCurrentSeason();

        if (null === $currentSeason) {
            throw new \Exception("Current season not found.");
        }

        if (null !== $currentSeason->getChampion()) {
            $this->logger->info('Driver championship for current season is already decided.');
            return;
        }

        $calculatedPredictions = $this->calculatePossibleWin($currentSeason);
        if ($calculatedPredictions === null) {
            $this->logger->info('No predictions for upcoming race calculated.');
        } else {
            $this->logger->info('New predictions for upcoming race calculated.');
        }
    }

    public function calculatePossibleWin(Season $season): ?Prediction
    {
        $drivers = $this->raceResultManager->getDriversByStandingsForSeason($season);

        $seasonRacesLeft = $season->getRaces() - $season->getCompletedRaces();
        $seasonSprintsLeft = $season->getSprints() - $season->getCompletedSprints();
        $maxPointsLeftForGrab = $this->calculateAvailablePoints($seasonRacesLeft, $seasonSprintsLeft);

        if (0 === $seasonRacesLeft) {
            $this->logger->error('No races for this season left.');
            return null;
        }

        /** @var \App\Entity\Driver $leadDriver */
        $leadDriver = array_shift($drivers);
        $relevantDrivers = array_filter(
            $drivers,
            fn (\App\Entity\Driver $driver) => (
                $driver->getSeasonPoints() + $maxPointsLeftForGrab > $leadDriver->getSeasonPoints()
            )
        );

        return $this->checkWinConditions(
            $leadDriver,
            $relevantDrivers,
            $seasonRacesLeft,
            $seasonSprintsLeft
        );
    }

    /**
     * Check win conditions for upcoming race
     *
     * @param Driver $leadDriver            Drivers standings leader
     * @param Driver[] $relevantDrivers     Drivers still in contention
     * @param int $racesRemaining           Remaining races in season
     * @param int $sprintsRemaining         Remaining sprints in season
     */
    private function checkWinConditions(
        Driver $leadDriver,
        array $relevantDrivers,
        int $racesRemaining,
        int $sprintsRemaining
    ): ?Prediction {
        if (count($relevantDrivers) == 0) {
            throw new \Exception('Unexpected empty relevantDrivers');
        }
        // TODO need to adjust $sprintsRemaining if it is a sprint weekend

        $pointsGapNeeded = $this->calculateAvailablePoints($racesRemaining - 1, $sprintsRemaining);
        $driversPointsDifference = $leadDriver->getSeasonPoints() - $relevantDrivers[0]->getSeasonPoints();

        // Check if the standings leader can possibly get a win during the current weekend
        if ($driversPointsDifference + self::POINTS_RACE_MAX < $pointsGapNeeded) {
            // Lead driver cannot achieve a win yet even with P1 + Fastest Lap (and sprint win TODO)
            return null;
        }

        $prediction = new Prediction();
        $prediction->setDriver($leadDriver);

        $this->checkWinConditionForPosition(
            $leadDriver,
            $relevantDrivers,
            $pointsGapNeeded,
            $prediction
        );

        $this->entityManager->persist($prediction);
        $this->entityManager->flush();

        return $prediction;
    }

    /**
     * Recursively check leading driver's winning condition for every point scoring race position
     *
     * @param Driver   $leader          Standings leader
     * @param Driver[] $contenders      Drivers still in contention
     * @param int $pointsGapNeeded      How many points does a leader need to become the champion
     * @param Prediction $prediction    Calculated predictions
     * @param int $position             Standings leader's predicted race finishing position
     *
     * @return Prediction
     */
    private function checkWinConditionForPosition(
        Driver $leader,
        array $contenders,
        int $pointsGapNeeded,
        Prediction $prediction,
        int $position = 1,
    ): Prediction {
        // TODO: handle sprint race weekends

        if ($position > count(self::getRacePointsForFinish())) {
            return $prediction;
        }

        $predictedLeaderPoints = self::getRacePointsForFinish()[$position];
        foreach ($contenders as $contender) {
            // Checking finishing position WITH fastest lap
            [$dropOutPosition, $_] = $this->checkHighestPositionToDropOut(
                $leader->getSeasonPoints() + $predictedLeaderPoints,
                $position,
                $contender,
                $pointsGapNeeded,
                true
            );
            $comparison = new PredictionComparison();
            $comparison
                ->setLeaderPosition($position)
                ->setLeaderFL(true)
                ->setContender($contender)
                ->setHighestPosition($dropOutPosition);
            $prediction->addComparison($comparison);

            // Checking finishing position WITHOUT fastest lap
            [$dropOutPosition, $withoutFL] = $this->checkHighestPositionToDropOut(
                $leader->getSeasonPoints() + $predictedLeaderPoints,
                $position,
                $contender,
                $pointsGapNeeded
            );
            $comparison = new PredictionComparison();
            $comparison
                ->setLeaderPosition($position)
                ->setLeaderFL(false)
                ->setContender($contender)
                ->setHighestPosition($dropOutPosition)
                ->setWithoutFL($withoutFL);
            $prediction->addComparison($comparison);
        }

        return $this->checkWinConditionForPosition(
            $leader,
            $contenders,
            $pointsGapNeeded,
            $prediction,
            $position + 1
        );
    }

    private function checkHighestPositionToDropOut(
        float $predictedLeaderPoints,
        int $leaderPosition,
        Driver $contender,
        int $maxPointsLeft,
        bool $withFastest = false
    ): array {
        $availableFinishingPositions = self::getRacePointsForFinish();
        unset($availableFinishingPositions[$leaderPosition]);

        if ($withFastest) {
            $predictedLeaderPoints += self::POINTS_RACE_FASTEST;
        }

        $i = 1;
        foreach ($availableFinishingPositions as $position => $points) {
            if (($pointsDiff = ($predictedLeaderPoints - ($contender->getSeasonPoints() + $points))) > $maxPointsLeft) {
                if ($i === 1) {
                    return [-1, false];
                }
                return [$position, false];
            }
            if ($pointsDiff == $maxPointsLeft) {
                if ($withFastest && ($i === 1 || $position === 10)) {
                    return [-1, false];
                }
                if ($withFastest) {
                    return [$position, false];
                }
                return [$position, true];
            }
            $i++;
        }
        return [
            count(self::getRacePointsForFinish()) + 1,
            false
        ];
    }
}
