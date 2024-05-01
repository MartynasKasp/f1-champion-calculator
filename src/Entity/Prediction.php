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

    public function __construct()
    {
        $this->setComparisons(new ArrayCollection());
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setDriver(Driver $driver): self
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

    public function addComparison($comparison): self
    {
        $this->comparisons->add($comparison);
        return $this;
    }

    public function setComparisons($comparisons): self
    {
        $this->comparisons = $comparisons;
        return $this;
    }
}
