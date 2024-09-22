<?php

namespace App\Command;

use App\Service\CalculatorManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:calculate:current-season')]
class CalculatePredictionsForCurrentSeasonCommand extends Command
{
    public function __construct(
        private CalculatorManager $calculatorManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->calculatorManager->calculate();
        } catch (\Exception $exception) {
            $output->writeln('<error>Exception occurred: ' . $exception->getMessage() . '</error>');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
