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
    public readonly string $constructor;

    /**
     * @param array{driverId:string,permanentNumber:string,code:string,url:string,givenName:string,familyName:string,dateOfBirth:string,nationality:string} $driver
     */
    public function setDriver(array $driver): static
    {
        $fullName = $driver['givenName'] . ' ' . $driver['familyName'];
        $this->driver = trim($fullName);

        return $this;
    }

    /**
     * @param array{constructorId:string,url:string,name:string,nationality:string} $constructor
     */
    public function setConstructor(array $constructor): static
    {
        $this->constructor = $constructor['name'];
        return $this;
    }
}
