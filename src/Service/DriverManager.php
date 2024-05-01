<?php

namespace App\Service;

use App\Entity\Driver;
use Doctrine\ORM\EntityManagerInterface;

class DriverManager
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function getDriverById(string $id): ?Driver
    {
        return null;
        // /** @var \App\Repository\DriverRepository $driverRepo */
        // $driverRepo = $this->documentManager->getRepository(Driver::class);
        // return $driverRepo->find($id);
    }
}
