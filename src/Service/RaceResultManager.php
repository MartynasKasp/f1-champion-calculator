<?php

namespace App\Service;

use App\Entity\Driver;
use App\Entity\Race;
use App\Entity\RaceResult;
use App\Entity\Season;
use App\Model\DTO\RaceResultDTO;
use App\Trait\LoggerInjector;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class RaceResultManager
{
    use LoggerInjector;

    /** @var \App\Repository\RaceResultRepository */
    protected EntityRepository $raceResultRepository;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private \ErgastAPI\Connector $ergastConnector,
    ) {
        $this->raceResultRepository = $this->entityManager->getRepository(RaceResult::class);
    }

    /**
     * @return RaceResultDTO[]
     */
    public function getDriversByStandingsForSeason(Season $season): array
    {
        /** @var \App\Repository\RaceResultRepository $raceResultRepo */
        $raceResultRepo = $this->entityManager->getRepository(RaceResult::class);
        /** @var \App\Repository\DriverRepository $driverRepo */
        $driverRepo = $this->entityManager->getRepository(Driver::class);

        return array_map(
            fn ($item) => new RaceResultDTO(
                driver: $driverRepo->find($item['driverId']),
                seasonPoints: $item['seasonPoints'],
            ),
            $raceResultRepo->getStandingsForSeason($season)
        );
    }

    public function findById(string $resultId): ?RaceResult
    {
        return $this->raceResultRepository->find($resultId);
    }

    public function importRaceResults(string $season, string $race): void
    {
        try {
            if ('current' === $season) {
                $season = (new \DateTimeImmutable())->format('Y');
            }

            /** @var \App\Repository\SeasonRepository $seasonRepo */
            $seasonRepo = $this->entityManager->getRepository(Season::class);
            /** @var \App\Repository\RaceRepository $raceRepo */
            $raceRepo = $this->entityManager->getRepository(Race::class);

            $seasonEntity = $seasonRepo->findSeasonById($season);
            if ('last' === $race) {
                $currentDate = new \DateTimeImmutable();

                $raceEntity = $raceRepo->getLastRaceByDate(
                    (new \DateTimeImmutable())->setDate(
                        $season,
                        $currentDate->format('n'),
                        $currentDate->format('j')
                    )
                );
            } else {
                $raceEntity = $raceRepo->getRaceForSeasonByStage($seasonEntity->getId(), $race);
            }

            if (null === $raceEntity) {
                $this->logger->error(
                    'Race results import: race does not exist. Skipping import.',
                    ['season' => $season, 'race_stage' => $race]
                );
                return;
            }

            if ($raceEntity->isCompleted()) {
                $this->logger->info(
                    'Race results import: race ' . $raceEntity->getId() . ' is already completed. Skipping import.',
                    ['race_date' => $raceEntity->getDate()->format('Y-m-d')]
                );
                return;
            }

            $raceResults = $this->ergastConnector->getRaceResults($season, $race);
            foreach ($raceResults as $result) {
                $raceResult = new RaceResult();
                $raceResult
                    ->setDriver($this->findOrCreateDriverForResult($result))
                    ->setPoints($result->points)
                    ->setPosition($result->position)
                    ->setRace($raceEntity)
                    ->setResultStatus($result->status)
                    ->setSeason($seasonEntity);

                $this->entityManager->persist($raceResult);
                $raceEntity->addResult($raceResult);
            }

            $raceEntity->setCompleted(true);
            $seasonEntity->setCompletedRaces($seasonEntity->getCompletedRaces() + 1);

            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error(
                'Race results import failed. Error: ' . $exception->getMessage(),
                ['season' => $season, 'race' => $race]
            );
        }
    }

    // public function importLastRaceResults(): void
    // {
    //     $this->importRaceResults('current', 'last');
    // }

    public function findOrCreateDriverForResult(
        \ErgastAPI\Model\RaceResultDTO $driver,
        bool $flush = false
    ): Driver {
        /** @var \App\Repository\DriverRepository $driverRepo */
        $driverRepo = $this->entityManager->getRepository(Driver::class);

        $find = $driverRepo->getDriverByFullName($driver->driver);
        if (null !== $find) {
            return $find;
        }

        $find = $driverRepo->getDriverByNumber($driver->number);
        if (null !== $find) {
            return $find;
        }

        $create = new Driver();
        $create
            ->setFullName($driver->driver)
            ->setNumber($driver->number)
            ->setTeam(null); // TODO
        $this->entityManager->persist($create);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $create;
    }

    /**
     * @return RaceResult[]
     */
    public function getRaceResults(Race $race): array
    {
        return $this->raceResultRepository->getResultsForRace($race);
    }
}
