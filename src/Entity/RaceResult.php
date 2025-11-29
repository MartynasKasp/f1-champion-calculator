<?php

namespace App\Entity;

use App\Repository\RaceResultRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceResultRepository::class)]
class RaceResult
{
    const RESULT_STATUS_DNF = 'DNF';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(type: "string")]
    protected ?string $id;

    #[ORM\ManyToOne]
    protected Season $season;

    #[ORM\ManyToOne]
    protected ?Driver $driver;

    #[ORM\Column(type: "integer")]
    protected int $position;

    #[ORM\Column(type: "float")]
    protected float $points = 0;

    // Finished, Retired, +1 laps, etc.
    #[ORM\Column(type: "string", nullable: true)]
    protected ?string $resultStatus = 'Finished';

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Race $race = null;

    #[ORM\ManyToOne]
    private ?Team $constructor;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): static
    {
        $this->race = $race;
        return $this;
    }

    public function getSeason(): Season
    {
        return $this->season;
    }

    public function setSeason(Season $season): static
    {
        $this->season = $season;
        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(Driver $driver): static
    {
        $this->driver = $driver;
        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;
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

    public function getResultStatus(): ?string
    {
        return $this->resultStatus;
    }

    public function setResultStatus(?string $resultStatus): static
    {
        $this->resultStatus = $resultStatus;
        return $this;
    }

    public function getConstructor(): ?Team
    {
        return $this->constructor;
    }

    public function setConstructor(Team $constructor): static
    {
        $this->constructor = $constructor;
        return $this;
    }
}
