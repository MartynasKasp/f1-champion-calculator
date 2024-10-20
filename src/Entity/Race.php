<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
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
    protected bool $fullDistance = false;

    #[ORM\Column(type: "boolean")]
    protected bool $sprintRace = false;

    #[ORM\ManyToOne]
    protected ?Season $season = null;

    #[ORM\Column(type: "string")]
    protected string $grandPrix;

    #[ORM\ManyToOne]
    protected ?Circuit $circuit = null;

    /**
     * @var Collection<int, RaceResult>
     */
    #[ORM\OneToMany(targetEntity: RaceResult::class, mappedBy: 'race')]
    private Collection $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

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

    public function getGrandPrix(): string
    {
        return $this->grandPrix;
    }

    public function setGrandPrix(string $grandPrix): static
    {
        $this->grandPrix = $grandPrix;
        return $this;
    }

    public function getCircuit(): ?Circuit
    {
        return $this->circuit;
    }

    public function setCircuit(Circuit $circuit): static
    {
        $this->circuit = $circuit;
        return $this;
    }

    /**
     * @return Collection<int, RaceResult>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function setResults(Collection $results): static
    {
        $this->results = $results;
        return $this;
    }

    public function addResult(RaceResult $result): static
    {
        if (!$this->results->contains($result)) {
            $this->results->add($result);
            $result
                ->setRace($this)
                ->setSeason($this->season)
            ;
        }

        return $this;
    }

    public function removeResult(RaceResult $result): static
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getRace() === $this) {
                $result->setRace(null);
            }
        }

        return $this;
    }
}
