<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: DriverRepository::class)]
class Driver
{
    #[MongoDB\Id(type: "string")]
    private string $number;

    #[MongoDB\Field(type: "string")]
    private string $fullName;

    #[MongoDB\Field(type: "float")]
    private float $points = 0;

    #[MongoDB\Field(type: "string", nullable: true)]
    private ?string $teamId = null;

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

    public function getPoints(): float
    {
        return $this->points;
    }

    public function setPoints(float $points): self
    {
        $this->points = $points;
        return $this;
    }

    public function addPoints(float $points)
    {
        $this->points += $points;
        return $this;
    }

    public function getTeamId(): ?string
    {
        return $this->teamId;
    }

    public function setTeamId(string $teamId): self
    {
        $this->teamId = $teamId;
        return $this;
    }
}
