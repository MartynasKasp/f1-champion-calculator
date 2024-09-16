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
        /** @var \App\Repository\DriverRepository $driverRepo */
        $driverRepo = $this->entityManager->getRepository(Driver::class);
        return $driverRepo->find($id);
    }
}
