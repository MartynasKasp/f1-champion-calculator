<?php

namespace App\Command;

use App\Document\Driver;
use App\Document\Team;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: "app:driver:create")]
class CreateDriverCommand extends Command
{
    public function __construct(private DocumentManager $documentManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $team = (new Team())
            ->setId('1')
            ->setName('Red Bull Racing');
        $this->documentManager->persist($team);

        $driver = new Driver();
        $driver
            ->setNumber('11')
            ->setFullName('Sergio Perez')
            ->setTeamId($team->getId())
            ->addPoints('253');

        $this->documentManager->persist($driver);
        $this->documentManager->flush();

        return 0;
    }
}
