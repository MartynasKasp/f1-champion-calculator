<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Driver
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(type: "string")]
    protected ?string $id;

    #[ORM\Column(type: "string")]
    protected string $number;

    #[ORM\Column(type: "string")]
    protected string $fullName;

    #[ORM\ManyToOne]
    protected ?Team $team = null;

    #[ORM\Column(type: "float")]
    protected float $seasonPoints = 0;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;
        return $this;
    }

    public function getSeasonPoints(): float
    {
        return $this->seasonPoints;
    }

    public function setSeasonPoints(float $seasonPoints): static
    {
        $this->seasonPoints = $seasonPoints;
        return $this;
    }

    public function addSeasonPoints(float $seasonPoints): static
    {
        $this->seasonPoints += $seasonPoints;
        return $this;
    }
}
