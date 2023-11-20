<?php

namespace Tests\Functional;

use App\Service\CalculatorManager;
use Tests\Support\FunctionalTester;

class CalculatorTest extends \Codeception\Test\Unit
{
    protected FunctionalTester $tester;

    public function testFor2022Season()
    {
        // Fixtures are set to simulate results before Japanese GP in 2022
        // Calculates predictions for Japanese GP in 2022

        $calculatorManager = $this->tester->grabService(CalculatorManager::class);
        $prediction = $calculatorManager->calculatePossibleWin();

        $this->assertEquals('1', $prediction['driver']);
        $this->assertArrayHasKey('comparison', $prediction);

        $this->assertEquals('3', $prediction['comparison']['1']['16']);
        $this->assertEquals('8-FL', $prediction['comparison']['5']['11']);
        $this->assertEquals('11', $prediction['comparison']['6']['16']);
        $this->assertEquals('11', $prediction['comparison']['7']['16']);
        $this->assertEquals('11', $prediction['comparison']['7']['11']);
    }
}
