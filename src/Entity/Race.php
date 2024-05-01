<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity] //(repositoryClass: TeamRepository::class)]
class Race
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(type: "string")]
    protected ?string $id;

    #[ORM\Column(type: "date_immutable")]
    protected \DateTimeImmutable $date;

    #[ORM\Column(type: "boolean")]
    protected bool $completed = false;

    #[ORM\Column(type: "boolean")]
    protected bool $canceled = false;

    #[ORM\Column(type: "boolean")]
    protected bool $fullDistance = true;

    #[ORM\Column(type: "boolean")]
    protected bool $sprintRace = false;

    #[ORM\ManyToOne]
    private ?Season $season = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(Season $season): static
    {
        $this->season = $season;
        return $this;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;
        return $this;
    }

    public function isCanceled(): bool
    {
        return $this->canceled;
    }

    public function setCanceled(bool $canceled): static
    {
        $this->canceled = $canceled;
        return $this;
    }

    public function isFullDistance(): bool
    {
        return $this->fullDistance;
    }

    public function setFullDistance(bool $fullDistance): static
    {
        $this->fullDistance = $fullDistance;
        return $this;
    }

    public function isSprintRace(): bool
    {
        return $this->sprintRace;
    }

    public function setSprintRace(bool $sprintRace): static
    {
        $this->sprintRace = $sprintRace;
        return $this;
    }
}
