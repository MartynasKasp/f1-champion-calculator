<?php

namespace App\Command\Results;

use ErgastAPI\Connector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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

        $io = new SymfonyStyle($input, $output);
        $io->newLine();

        $resultsInfo = $this->ergastApi->checkRaceResultsInfo($season, $race);
        $output->writeln('<info>Race:</info> ' . $resultsInfo['raceName']);
        $output->writeln('<info>Date:</info> ' . $resultsInfo['date']);
        $output->writeln('<info>Round:</info> ' . $resultsInfo['round']);

        $tabelRows = [];
        foreach ($resultsInfo['Results'] as $result) {
            $tabelRows[] = [
                $result['position'],
                ($driver = $result['Driver'])['givenName'] . ' ' . $driver['familyName'],
                $result['points'],
                $result['status']
            ];
        }
        $io->newLine();
        $io->table(['Pos.', 'Driver', 'Points', 'Result'], $tabelRows);

        return 0;
    }
}
