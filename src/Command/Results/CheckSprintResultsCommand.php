<?php

namespace App\Command\Results;

use ErgastAPI\Connector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand("app:sprint-results:check")]
class CheckSprintResultsCommand extends Command
{
    public function __construct(private Connector $ergastApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('season', 's', InputOption::VALUE_REQUIRED, 'Season', 'current')
            ->addOption('round', 'r', InputOption::VALUE_REQUIRED, 'Round stage of the season', 'last')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $season = $input->getOption('season');
        $round = $input->getOption('round');

        $io = new SymfonyStyle($input, $output);
        $io->newLine();

        $resultsInfo = $this->ergastApi->checkSprintResultsInfo($season, $round);
        $output->writeln('<info>Race:</info> ' . $resultsInfo['raceName']);
        $output->writeln('<info>Date:</info> ' . $resultsInfo['date']);
        $output->writeln('<info>Round:</info> ' . $resultsInfo['round']);

        $tabelRows = [];
        foreach ($resultsInfo['SprintResults'] as $result) {
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
