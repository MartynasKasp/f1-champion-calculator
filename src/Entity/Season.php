<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeasonRepository::class)]
class Season
{
    #[ORM\Id]
    #[ORM\Column(type: "string")]
    protected ?string $id;

    #[ORM\Column(type: "date")]
    protected \DateTime $startsAt;

    #[ORM\Column(type: "date")]
    protected \DateTime $endsAt;

    #[ORM\Column(type: "integer")]
    protected int $races;

    #[ORM\Column(type: "integer")]
    protected int $sprints;

    #[ORM\Column(type: "integer")]
    protected int $completedRaces = 0;

    #[ORM\Column(type: "integer")]
    protected int $completedSprints = 0;

    #[ORM\ManyToOne]
    protected ?Driver $champion = null;

    public function getId(): ?string
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

    public function getChampion(): ?Driver
    {
        return $this->champion;
    }

    public function setChampion(?Driver $champion): static
    {
        $this->champion = $champion;
        return $this;
    }
}
