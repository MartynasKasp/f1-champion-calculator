<?php

namespace Tests\Functional;

use App\Entity\Season;
use App\Service\CalculatorManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Tests\Support\FunctionalTester;

class CalculatorTest extends \Codeception\Test\Unit
{
    protected FunctionalTester $tester;

    public function testFor2022Season()
    {
        // Fixtures are set to simulate results before Japanese GP in 2022
        // Calculates predictions for Japanese GP in 2022

        /** @var DocumentManager $documentManager */
        $documentManager = $this->tester->grabService(DocumentManager::class);
        /** @var \App\Repository\SeasonRepository $seasonRepository */
        $seasonRepository = $documentManager->getRepository(Season::class);
        $season2022 = $seasonRepository->findSeasonInPeriod(new \DateTimeImmutable('2022-10-03'));

        $this->assertEquals('2022', $season2022->getId());

        /** @var CalculatorManager $calculatorManager */
        $calculatorManager = $this->tester->grabService(CalculatorManager::class);
        $prediction = $calculatorManager->calculatePossibleWin($season2022);

        $this->assertNotNull($prediction);
        /** @var \App\Entity\Prediction $prediction */
        $this->assertEquals('1', $prediction->getDriverId());
        $this->assertNotEmpty($prediction->getComparisons());
        $this->assertEquals(40, count($prediction->getComparisons()));

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
}
