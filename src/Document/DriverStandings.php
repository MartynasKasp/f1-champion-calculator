<?php

namespace App\Document;

use App\Repository\DriverStandingsRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: DriverStandingsRepository::class)]
class DriverStandings
{
    #[MongoDB\Id(type: "string", strategy: "UUID")]
    private string $id;

    #[MongoDB\ReferenceOne(targetDocument: Driver::class)]
    private Driver $driver;

    #[MongoDB\ReferenceOne(targetDocument: Season::class, nullable: true)]
    private ?Season $season = null;

    #[MongoDB\Field(type: "float")]
    private float $points = 0;

    #[MongoDB\Field(type: "string", nullable: true)]
    private ?string $teamId = null;

    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function setDriver(Driver $driver): static
    {
        $this->driver = $driver;
        return $this;
    }

    public function getPoints(): float
    {
        return $this->points;
    }

    public function setPoints(float $points): static
    {
        $this->points = $points;
        return $this;
    }

    public function addPoints(float $points): static
    {
        $this->points += $points;
        return $this;
    }

    public function getTeamId(): ?string
    {
        return $this->teamId;
    }

    public function setTeamId(?string $teamId): static
    {
        $this->teamId = $teamId;
        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): static
    {
        $this->season = $season;
        return $this;
    }
}
