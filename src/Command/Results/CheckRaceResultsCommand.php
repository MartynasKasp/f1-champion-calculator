<?php

namespace App\Command\Results;

use ErgastAPI\Connector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand("app:race-results:check")]
class CheckRaceResultsCommand extends Command
{
    public function __construct(private Connector $ergastApi)
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

        $resultsInfo = $this->ergastApi->checkRaceResultsInfo($season, $race);
        $output->writeln('Race: ' . $resultsInfo['raceName']);
        $output->writeln('Date: ' . $resultsInfo['date']);
        $output->writeln('Round: ' . $resultsInfo['round']);

        return 0;
    }
}
