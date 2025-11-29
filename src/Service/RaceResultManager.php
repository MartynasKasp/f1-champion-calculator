<?php

namespace App\Service;

use App\Entity\Driver;
use App\Entity\Race;
use App\Entity\RaceResult;
use App\Entity\Season;
use App\Entity\Team;
use App\Model\DTO\RaceResultDTO;
use App\Repository\RaceResultRepository;
use App\Trait\LoggerInjector;
use Doctrine\ORM\EntityManagerInterface;

class RaceResultManager
{
    use LoggerInjector;

    protected RaceResultRepository $raceResultRepository;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private \ErgastAPI\Connector $ergastConnector,
    ) {
        /** @var RaceResultRepository */
        $repo = $this->entityManager->getRepository(RaceResult::class);
        $this->raceResultRepository = $repo;
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
        $results = $raceResultRepo->getStandingsForSeason($season);

        $standings = [];
        foreach ($results as $result) {
            $diff = ((int)$results[0]['seasonPoints']) - ((int)$result['seasonPoints']);
            $standings[] = new RaceResultDTO(
                driver: $driverRepo->find($result['driverId']),
                seasonPoints: (float) $result['seasonPoints'],
                diffToLeader: $diff > 0 ? $diff : null
            );
        }

        return $standings;
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
                        (int) $season,
                        (int) $currentDate->format('n'),
                        (int) $currentDate->format('j')
                    )
                );
            } else {
                $raceEntity = $raceRepo->getRaceForSeasonByStage($seasonEntity->getId(), (int) $race);
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
                try {
                    $raceResult = new RaceResult();
                    $raceResult
                        ->setConstructor($this->findOrCreateConstructorFromResult($result, true))
                        ->setDriver($this->findOrCreateDriverForResult($result))
                        ->setPoints((float) $result->points)
                        ->setPosition((int) $result->position)
                        ->setRace($raceEntity)
                        ->setResultStatus($result->status)
                        ->setSeason($seasonEntity)
                    ;

                    $this->entityManager->persist($raceResult);
                    $raceEntity->addResult($raceResult);
                } catch (\Exception $exception) {
                    $this->logger->error(
                        'Race result import failed. Skipping. Error: ' . $exception->getMessage(),
                        ['result' => json_encode($result), 'season' => $season, 'race' => $race]
                    );
                    continue;
                }
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

    public function importSprintResults(string $season, string $race, string $sprint): void
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
                        (int) $season,
                        (int) $currentDate->format('n'),
                        (int) $currentDate->format('j')
                    ),
                    true
                );
            } else {
                $raceEntity = $raceRepo->getRaceForSeasonByStage($seasonEntity->getId(), (int) $sprint, true);
            }

            if (null === $raceEntity) {
                $this->logger->error(
                    'Sprint results import: race does not exist. Skipping import.',
                    ['season' => $season, 'race_stage' => $race]
                );
                return;
            }

            if ($raceEntity->isCompleted()) {
                $this->logger->info(
                    'Sprint results import: race ' . $raceEntity->getId() . ' is already completed. Skipping import.',
                    ['race_date' => $raceEntity->getDate()->format('Y-m-d')]
                );
                return;
            }

            $raceResults = $this->ergastConnector->getSprintResults($season, $race);
            foreach ($raceResults as $result) {
                try {
                    $raceResult = new RaceResult();
                    $raceResult
                        ->setConstructor($this->findOrCreateConstructorFromResult($result, true))
                        ->setDriver($this->findOrCreateDriverForResult($result))
                        ->setPoints((float) $result->points)
                        ->setPosition((int) $result->position)
                        ->setRace($raceEntity)
                        ->setResultStatus($result->status)
                        ->setSeason($seasonEntity);

                    $this->entityManager->persist($raceResult);
                    $raceEntity->addResult($raceResult);
                } catch (\Exception $exception) {
                    $this->logger->error(
                        'Sprint result import failed. Skipping. Error: ' . $exception->getMessage(),
                        ['result' => json_encode($result), 'season' => $season, 'race' => $race]
                    );
                    continue;
                }
            }

            $raceEntity->setCompleted(true);
            $seasonEntity->increaseCompletedSprints();

            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error(
                'Sprint results import failed. Error: ' . $exception->getMessage(),
                ['season' => $season, 'race' => $race]
            );
        }
    }

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

        $teamRepo = $this->entityManager->getRepository(Team::class);
        $findTeam = $teamRepo->findOneBy(['name' => $driver->constructor]);

        $create = new Driver();
        $create
            ->setFullName($driver->driver)
            ->setNumber($driver->number)
            ->setTeam($findTeam);
        $this->entityManager->persist($create);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $create;
    }

    public function findOrCreateConstructorFromResult(
        \ErgastAPI\Model\RaceResultDTO $result,
        bool $flush = false
    ): \App\Entity\Team {
        $teamRepo = $this->entityManager->getRepository(Team::class);
        $findTeam = $teamRepo->findOneBy(['name' => $result->constructor]);

        if (null !== $findTeam) {
            return $findTeam;
        }

        $create = new Team();
        $create->setName($result->constructor);
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
