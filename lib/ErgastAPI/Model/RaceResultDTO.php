<?php

namespace ErgastAPI\Model;

class RaceResultDTO
{
    const STATUS_FINISHED = 'Finished';
    const STATUS_RETIRED = 'Retired';

    public string $number;
    public string $position;
    public string $points;
    public string $status;
    
    public readonly string $driver;

    public function setDriver(array $driver): self
    {
        $fullName = $driver['givenName'] . ' ' . $driver['familyName'];
        $this->driver = trim($fullName);

        return $this;
    }
}
