<?php

namespace App\Document\Driver;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class Driver
{
    #[MongoDB\Id(type: "string", strategy: "UUID")]
    private string $id;

    #[MongoDB\Field(type: "string")]
    private string $number;

    #[MongoDB\Field(type: "string")]
    private string $fullName;

    #[MongoDB\Field(type: "float")]
    private string $points = '0';

    #[MongoDB\Field(type: "string")]
    private string $team;

    public function getId(): string
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getPoints(): string
    {
        return $this->points;
    }

    public function setPoints(string $points): self
    {
        $this->points = $points;
        return $this;
    }

    public function addPoints(string $points)
    {
        $this->points += $points;
        return $this;
    }

    public function getTeam(): string
    {
        return $this->team;
    }

    public function setTeam(string $team): self
    {
        $this->team = $team;
        return $this;
    }
}
