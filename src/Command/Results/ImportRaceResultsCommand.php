<?php

namespace App\Command\Results;

use App\Service\RaceResultManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand("app:race-results:import")]
class ImportRaceResultsCommand extends Command
{
    public function __construct(private RaceResultManager $manager)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addOption('season', 's', InputOption::VALUE_REQUIRED, 'Season', 'current')
            ->addOption('race', 'r', InputOption::VALUE_REQUIRED, 'Race stage of the season', 'last')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $season = $input->getOption('season');
        $race = $input->getOption('race');

        $this->manager->importRaceResults($season, $race);

        return 0;
    }
}
