<?php

namespace App\Service;

use App\Document\Driver;
use App\Document\Prediction;
use App\Document\PredictionComparison;
use Doctrine\ODM\MongoDB\DocumentManager;

class CalculatorManager
{
    const POINTS_RACE_P1 = 25;
    const POINTS_RACE_P10 = 1;
    const POINTS_RACE_FASTEST = 1;
    const POINTS_RACE_MAX = self::POINTS_RACE_P1 + self::POINTS_RACE_FASTEST;

    const POINTS_SPRINT_P1 = 8;

    public function __construct(
        private DocumentManager $documentManager
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

    public function calculatePossibleWin(): ?Prediction
    {
        // TODO need to check if there is no champion yet to prevent calculation for upcoming races

        /** @var \App\Repository\DriverRepository $driverRepository */
        $driverRepository = $this->documentManager->getRepository(Driver::class);
        $drivers = $driverRepository->getDriversByStandings();

        $seasonRacesLeft = 5;
        $seasonSprintsLeft = 1;
        $maxPointsLeftForGrab = $this->calculateAvailablePoints($seasonRacesLeft, $seasonSprintsLeft);

        /** @var \App\Document\Driver $leadDriver */
        $leadDriver = array_shift($drivers);
        $relevantDrivers = array_filter(
            $drivers,
            fn (Driver $driver) => ($driver->getPoints() + $maxPointsLeftForGrab > $leadDriver->getPoints())
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
        $driversPointsDifference = $leadDriver->getPoints() - $relevantDrivers[0]->getPoints();

        // Check if the standings leader can possibly get a win during the current weekend
        if ($driversPointsDifference + self::POINTS_RACE_MAX < $pointsGapNeeded) {
            // Lead driver cannot achieve a win yet even with P1 + Fastest Lap (and sprint win TODO)
            return null;
        }

        $prediction = new Prediction();
        $prediction->setDriverId($leadDriver->getNumber());

        $this->checkWinConditionForPosition(
            $leadDriver,
            $relevantDrivers,
            $pointsGapNeeded,
            $prediction
        );

        $this->documentManager->persist($prediction);
        $this->documentManager->flush();

        return $prediction;
    }

    /**
     * Recursively check leading driver's winning condition for every point scoring race position
     *
     * @param Driver $leader        Standings leader
     * @param Driver[] $contenders  Drivers still in contention
     * @param int $pointsGapNeeded  How many points does a leader need to become the champion
     * @param int $position         Standings leader's predicted race finishing position
     * @param array $winConditions  Win conditions calculated
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
                $leader->getPoints() + $predictedLeaderPoints,
                $position,
                $contender,
                $pointsGapNeeded,
                true
            );
            $comparison = new PredictionComparison();
            $comparison
                ->setLeaderPosition($position)
                ->setLeaderFL(true)
                ->setContenderId($contender->getNumber())
                ->setHighestPosition($dropOutPosition);
            $prediction->addComparison($comparison);

            // Checking finishing position WITHOUT fastest lap
            [$dropOutPosition, $withoutFL] = $this->checkHighestPositionToDropOut(
                $leader->getPoints() + $predictedLeaderPoints,
                $position,
                $contender,
                $pointsGapNeeded
            );
            $comparison = new PredictionComparison();
            $comparison
                ->setLeaderPosition($position)
                ->setLeaderFL(false)
                ->setContenderId($contender->getNumber())
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
            if (($pointsDiff = ($predictedLeaderPoints - ($contender->getPoints() + $points))) > $maxPointsLeft) {
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
