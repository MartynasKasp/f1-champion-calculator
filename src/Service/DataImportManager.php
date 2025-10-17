<?php

namespace App\Service;

use App\Entity\Circuit;
use App\Entity\Race;
use App\Entity\Season;
use App\Trait\LoggerInjector;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DataImportManager
{
    use LoggerInjector;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private SeasonManager $seasonManager,
        private CircuitManager $circuitManager,
    ) {
    }

    /**
     * @return array<string>
     */
    public static function seasonDataFileHeaders(): array
    {
        return ['race_date', 'grand_prix', 'is_sprint', 'circuit', 'country'];
    }

    /**
     * @throws \Exception
     */
    public function importSeasonData(UploadedFile $uploadedFile): void
    {
        $splFile = $uploadedFile->openFile('r');
        $splFile->setFlags(\SplFileObject::READ_CSV);

        $this->validateFileHeaders($splFile, self::seasonDataFileHeaders());

        $season = null;
        $sprints = 0;
        $races = 0;
        $firstRaceDate = null;
        $lastRaceDate = null;

        $i = 0;
        foreach ($splFile as $row) {
            if ($i++ == 0) {
                continue; // headers
            }
            $line = array_combine(self::seasonDataFileHeaders(), $row);

            try {
                $date = new \DateTimeImmutable($line['race_date']);
            } catch (\Throwable $error) {
                $this->logger->error('Season data import: invalid date', [
                    'line' => $i, 'error' => $error->getMessage()
                ]);
                continue;
            }

            if (null === $firstRaceDate) {
                $firstRaceDate = $date;
            }
            if ($firstRaceDate > $date) {
                $firstRaceDate = $date;
            }

            if (null === $lastRaceDate) {
                $lastRaceDate = $date;
            }
            if ($lastRaceDate < $date) {
                $lastRaceDate = $date;
            }

            if (null === $season) {
                $season = $this->seasonManager->findSeasonById($date->format('Y'));

                if (null === $season) {
                    $season = (new Season())
                        ->setId($date->format('Y'))
                        ->setStartsAt($date);

                    $this->entityManager->persist($season);
                }
            }

            $circuit = $this->circuitManager->findCircuit($line['circuit']);
            if (null === $circuit) {
                $circuit = (new Circuit())
                    ->setCircuit($line['circuit'])
                    ->setCountry($line['country']);
                $this->entityManager->persist($circuit);
            }

            $race = (new Race())
                ->setDate($date)
                ->setGrandPrix($line['grand_prix'])
                ->setSeason($season)
                ->setSprintRace((bool) $line['is_sprint'])
                ->setCircuit($circuit);
            $this->entityManager->persist($race);

            if ((bool) $line['is_sprint']) {
                $sprints++;
            } else {
                $races++;
            }

            $this->entityManager->flush();
        }

        $season
            ->setRaces($races)
            ->setSprints($sprints)
            ->setStartsAt($firstRaceDate)
            ->setEndsAt($lastRaceDate);

        $this->entityManager->flush();
    }

    /**
     * @param array<string> $expectedHeaders
     */
    private function validateFileHeaders(\SplFileObject $splFile, array $expectedHeaders): void
    {
        $fileHeaders = $splFile->fgetcsv();
        $errors = [];

        foreach ($expectedHeaders as $header) {
            if (!in_array($header, $fileHeaders)) {
                $errors[] = 'missing ' . $header;
            }
        }
        $uncheckedHeaders = array_diff($fileHeaders, $expectedHeaders);
        foreach ($uncheckedHeaders as $header) {
            $errors[] = 'unrecognised ' . $header;
        }

        if (count($errors) > 0) {
            throw new \Exception('Invalid file headers: ' . json_encode($errors));
        }
    }
}
