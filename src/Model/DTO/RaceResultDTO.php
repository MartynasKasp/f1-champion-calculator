<?php

namespace App\Model\DTO;

use App\Entity\Driver;

class RaceResultDTO
{
    public function __construct(
        public Driver $driver,
        public float $seasonPoints,
    ) {
    }
}
