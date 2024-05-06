<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Prediction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(type: "string")]
    protected ?string $id;

    #[ORM\ManyToOne]
    protected Driver $driver;

    #[ORM\OneToMany(PredictionComparison::class, mappedBy: "id")]
    protected Collection $comparisons;

    #[ORM\ManyToOne]
    protected Race $race;

    public function __construct()
    {
        $this->setComparisons(new ArrayCollection());
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setDriver(Driver $driver): static
    {
        $this->driver = $driver;
        return $this;
    }

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function getComparisons()
    {
        return $this->comparisons;
    }

    public function addComparison($comparison): static
    {
        $this->comparisons->add($comparison);
        return $this;
    }

    public function setComparisons($comparisons): static
    {
        $this->comparisons = $comparisons;
        return $this;
    }

    public function getRace(): Race
    {
        return $this->race;
    }

    public function setRace(Race $race): static
    {
        $this->race = $race;
        return $this;
    }
}
