<?php

namespace App\Command;

use App\Document\Driver;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDriverCommand extends Command
{
    protected static $defaultName = 'app:driver:create';

    public function __construct(private DocumentManager $documentManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $driver = new Driver();
        $driver
            ->setNumber('11')
            ->setFullName('Sergio Perez')
            ->setTeam('RedBull Racing')
            ->addPoints('253');

        $this->documentManager->persist($driver);
        $this->documentManager->flush();

        return 0;
    }
}
