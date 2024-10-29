<?php

namespace Tests\Functional;

use App\Entity\Race;
use App\Entity\RaceResult;
use App\Entity\Season;
use App\Service\CalculatorManager;
use Doctrine\ORM\EntityManagerInterface;
use Tests\Support\FunctionalTester;

class CalculatorTest extends \Codeception\Test\Unit
{
    protected FunctionalTester $tester;
    protected EntityManagerInterface $entityManager;

    protected function _before()
    {
        $this->entityManager = $this->tester->grabService(EntityManagerInterface::class);
    }

    public function testNoComparisons()
    {
        /** @var \App\Repository\RaceRepository */
        $raceRepo = $this->entityManager->getRepository(Race::class);
        /** @var \App\Repository\RaceResultRepository */
        $raceResultRepo = $this->entityManager->getRepository(RaceResult::class);
        /** @var \App\Repository\SeasonRepository */
        $seasonRepo = $this->entityManager->getRepository(Season::class);

        /** @var \App\Entity\Season */
        $season22 = $seasonRepo->find('2022');
        $season22->setCompletedRaces(0);

        /** @var \App\Entity\Race */
        $race16 = $raceRepo->findOneBy(['season' => '2022', 'grandPrix' => 'Italy']);
        $race16->setCompleted(false);
        $raceResults16 = $raceResultRepo->findBy(['race' => $race16->getId()]);

        /** @var \App\Entity\Race */
        $race17 = $raceRepo->findOneBy(['season' => '2022', 'grandPrix' => 'Singapore']);
        $race17->setCompleted(false);
        $raceResults17 = $raceResultRepo->findBy(['race' => $race17->getId()]);

        foreach (array_merge($raceResults16, $raceResults17) as $result) {
            $this->entityManager->remove($result);
        }
        $this->entityManager->flush();

        /** @var \App\Repository\SeasonRepository $seasonRepository */
        $seasonRepository = $this->entityManager->getRepository(Season::class);
        $season2022 = $seasonRepository->findSeasonInPeriod(new \DateTimeImmutable('2022-10-03'));

        /** @var CalculatorManager $calculatorManager */
        $calculatorManager = $this->tester->grabService(CalculatorManager::class);
        $prediction = $calculatorManager->calculatePossibleWin($season2022);

        $this->assertNotNull($prediction);
        $this->assertCount(0, $prediction->getComparisons());
    }

    public function testFor2022Season()
    {
        // Fixtures are set to simulate results before Japanese GP in 2022
        // Calculates predictions for Japanese GP in 2022

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->tester->grabService(EntityManagerInterface::class);
        /** @var \App\Repository\SeasonRepository $seasonRepository */
        $seasonRepository = $entityManager->getRepository(Season::class);
        $season2022 = $seasonRepository->findSeasonInPeriod(new \DateTimeImmutable('2022-10-03'));

        $this->assertNotNull($season2022);
        $this->assertEquals('2022', $season2022->getId());

        /** @var CalculatorManager $calculatorManager */
        $calculatorManager = $this->tester->grabService(CalculatorManager::class);
        $prediction = $calculatorManager->calculatePossibleWin($season2022);

        $this->assertNotNull($prediction);
        /** @var \App\Entity\Prediction $prediction */
        $this->assertEquals('1', $prediction->getDriver()->getNumber());
        $this->assertNotEmpty($prediction->getComparisons());
        $this->assertEquals(40, count($prediction->getComparisons()));

        $this->assertEquals('16', $prediction->getComparisons()[0]->getContender()->getNumber());
        $this->assertEquals('11', $prediction->getComparisons()[2]->getContender()->getNumber());

        $this->assertEquals('-1', $prediction->getComparisons()[0]->getHighestPosition());
        $this->assertEquals('3', $prediction->getComparisons()[1]->getHighestPosition());
        $this->assertEquals('5', $prediction->getComparisons()[5]->getHighestPosition());
        $this->assertTrue($prediction->getComparisons()[5]->isWithoutFL());
        $this->assertEquals('4', $prediction->getComparisons()[7]->getHighestPosition());
        $this->assertTrue($prediction->getComparisons()[7]->isWithoutFL());
        $this->assertEquals('5', $prediction->getComparisons()[10]->getHighestPosition());
        $this->assertEquals('8', $prediction->getComparisons()[19]->getHighestPosition());
        $this->assertTrue($prediction->getComparisons()[19]->isWithoutFL());
        $this->assertEquals('11', $prediction->getComparisons()[21]->getHighestPosition());
        $this->assertEquals('11', $prediction->getComparisons()[25]->getHighestPosition());
        $this->assertEquals('11', $prediction->getComparisons()[27]->getHighestPosition());
    }

    public function testFor2023Season()
    {
        // Fixtures are set to simulate results before Qatar GP Sprint Race in 2023
        // Calculates predictions for Qatar GP Sprint Race in 2023

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->tester->grabService(EntityManagerInterface::class);
        /** @var \App\Repository\SeasonRepository $seasonRepository */
        $seasonRepository = $entityManager->getRepository(Season::class);
        $season = $seasonRepository->findSeasonInPeriod(new \DateTimeImmutable('2023-10-01'));

        $this->assertNotNull($season);
        $this->assertEquals('2023', $season->getId());

        /** @var CalculatorManager $calculatorManager */
        $calculatorManager = $this->tester->grabService(CalculatorManager::class);
        $prediction = $calculatorManager->calculatePossibleWin($season);

        $this->assertNotNull($prediction);
        /** @var \App\Entity\Prediction $prediction */
        $this->assertEquals('1', $prediction->getDriver()->getNumber());
        $this->assertNotEmpty($prediction->getComparisons());
        $this->assertEquals(8, count($prediction->getComparisons()));

        $this->assertEquals('11', $prediction->getComparisons()[0]->getContender()->getNumber());

        $this->assertEquals('-1', $prediction->getComparisons()[0]->getHighestPosition());
        $this->assertEquals('-1', $prediction->getComparisons()[1]->getHighestPosition());
        $this->assertEquals('-1', $prediction->getComparisons()[2]->getHighestPosition());
        $this->assertEquals('-1', $prediction->getComparisons()[3]->getHighestPosition());
        $this->assertEquals('-1', $prediction->getComparisons()[4]->getHighestPosition());
        $this->assertEquals('-1', $prediction->getComparisons()[5]->getHighestPosition());
        $this->assertEquals('1', $prediction->getComparisons()[6]->getHighestPosition());
        $this->assertEquals('2', $prediction->getComparisons()[7]->getHighestPosition());
    }
}
