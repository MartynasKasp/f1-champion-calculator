<?php

namespace App\Document;

use App\Repository\SeasonRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: SeasonRepository::class)]
class Season
{
    #[MongoDB\Id(type: "string", strategy: "none")]
    private string $id;

    #[MongoDB\Field(type: "date")]
    private \DateTime $startsAt;

    #[MongoDB\Field(type: "date")]
    private \DateTime $endsAt;

    #[MongoDB\Field(type: "int")]
    private int $races;

    #[MongoDB\Field(type: "int")]
    private int $sprints;

    #[MongoDB\Field(type: "int")]
    private int $completedRaces = 0;

    #[MongoDB\Field(type: "int")]
    private int $completedSprints = 0;

    #[MongoDB\Field(type: "string", nullable: true)]
    private ?string $champion = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getStartsAt(): \DateTime
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTime $startsAt): static
    {
        $this->startsAt = $startsAt;
        return $this;
    }

    public function getEndsAt(): \DateTime
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTime $endsAt): static
    {
        $this->endsAt = $endsAt;
        return $this;
    }

    public function getRaces(): int
    {
        return $this->races;
    }

    public function setRaces(int $races): static
    {
        $this->races = $races;
        return $this;
    }

    public function getSprints(): int
    {
        return $this->sprints;
    }

    public function setSprints(int $sprints): static
    {
        $this->sprints = $sprints;
        return $this;
    }

    public function getCompletedRaces(): int
    {
        return $this->completedRaces;
    }

    public function setCompletedRaces(int $completedRaces): static
    {
        $this->completedRaces = $completedRaces;
        return $this;
    }

    public function getCompletedSprints(): int
    {
        return $this->completedSprints;
    }

    public function setCompletedSprints(int $completedSprints): static
    {
        $this->completedSprints = $completedSprints;
        return $this;
    }

    public function getChampion(): ?string
    {
        return $this->champion;
    }

    public function setChampion(?string $champion): static
    {
        $this->champion = $champion;
        return $this;
    }
}
