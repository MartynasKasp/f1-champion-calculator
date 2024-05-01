<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PredictionComparison
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(type: "string")]
    protected ?string $id;

    #[ORM\Column(type: "integer")]
    protected int $leaderPosition;

    #[ORM\Column(type: "boolean")]
    protected bool $leaderFL;

    #[ORM\ManyToOne]
    protected Driver $contender;

    #[ORM\Column(type: "integer")]
    protected int $highestPosition;

    #[ORM\Column(type: "boolean")]
    protected bool $withoutFL = false;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLeaderPosition(): int
    {
        return $this->leaderPosition;
    }

    public function setLeaderPosition(int $leaderPosition): self
    {
        $this->leaderPosition = $leaderPosition;
        return $this;
    }

    public function isLeaderFL(): bool
    {
        return $this->leaderFL;
    }

    public function setLeaderFL(bool $leaderFL): self
    {
        $this->leaderFL = $leaderFL;
        return $this;
    }

    public function getContender(): Driver
    {
        return $this->contender;
    }

    public function setContender(Driver $contender): self
    {
        $this->contender = $contender;
        return $this;
    }

    public function getHighestPosition(): int
    {
        return $this->highestPosition;
    }

    public function setHighestPosition(int $highestPosition): self
    {
        $this->highestPosition = $highestPosition;
        return $this;
    }

    public function isWithoutFL(): bool
    {
        return $this->withoutFL;
    }

    public function setWithoutFL(bool $withoutFL): self
    {
        $this->withoutFL = $withoutFL;
        return $this;
    }
}
