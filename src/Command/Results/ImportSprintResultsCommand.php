<?php

namespace App\Command\Results;

use App\Service\RaceResultManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand("app:sprint-results:import")]
class ImportSprintResultsCommand extends Command
{
    public function __construct(private RaceResultManager $manager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('season', 's', InputOption::VALUE_REQUIRED, 'Season', 'current')
            ->addOption('round', 'r', InputOption::VALUE_REQUIRED, 'Round stage of the season', 'last')
            ->addOption('sprint', 'p', InputOption::VALUE_REQUIRED, 'Sprint stage of the season', 'last')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $season = $input->getOption('season');
        $round = $input->getOption('round');
        $sprint = $input->getOption('sprint');

        $this->manager->importSprintResults($season, $round, $sprint);

        return 0;
    }
}
