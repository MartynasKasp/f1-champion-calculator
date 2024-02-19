<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\EmbeddedDocument]
class PredictionComparison
{
    #[MongoDB\Id(type: "string", strategy: "UUID")]
    protected ?string $id = null;

    #[MongoDB\Field(type: "int")]
    protected int $leaderPosition;

    #[MongoDB\Field(type: "bool")]
    protected bool $leaderFL;

    #[MongoDB\Field(type: "string")]
    protected string $contenderId;

    #[MongoDB\Field(type: "int")]
    protected int $highestPosition;

    #[MongoDB\Field(type: "bool")]
    protected bool $withoutFL = false;

    public function getId(): string
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

    public function getContenderId(): string
    {
        return $this->contenderId;
    }

    public function setContenderId(string $contenderId): self
    {
        $this->contenderId = $contenderId;
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
