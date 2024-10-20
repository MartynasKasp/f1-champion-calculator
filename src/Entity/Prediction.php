<?php

namespace App\Entity;

use App\Repository\PredictionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PredictionRepository::class)]
class Prediction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(type: "string")]
    protected ?string $id;

    #[ORM\ManyToOne]
    protected Driver $driver;

    #[ORM\OneToMany(PredictionComparison::class, mappedBy: "prediction")]
    protected Collection $comparisons;

    #[ORM\ManyToOne]
    protected Race $race;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    protected ?\DateTimeImmutable $createdAt = null;

    // /**
    //  * @var Collection<int, PredictionComparison>
    //  */
    // #[ORM\OneToMany(targetEntity: PredictionComparison::class, mappedBy: 'prediction')]
    // private Collection $stuff;

    public function __construct()
    {
        $this->setComparisons(new ArrayCollection());
        $this->createdAt = new \DateTimeImmutable();
        // $this->stuff = new ArrayCollection();
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

    public function addComparison(PredictionComparison $comparison): static
    {
        $this->comparisons->add($comparison);
        $comparison->setPrediction($this);

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    // /**
    //  * @return Collection<int, PredictionComparison>
    //  */
    // public function getStuff(): Collection
    // {
    //     return $this->stuff;
    // }

    // public function addStuff(PredictionComparison $stuff): static
    // {
    //     if (!$this->stuff->contains($stuff)) {
    //         $this->stuff->add($stuff);
    //         $stuff->setPrediction($this);
    //     }

    //     return $this;
    // }

    // public function removeStuff(PredictionComparison $stuff): static
    // {
    //     if ($this->stuff->removeElement($stuff)) {
    //         // set the owning side to null (unless already changed)
    //         if ($stuff->getPrediction() === $this) {
    //             $stuff->setPrediction(null);
    //         }
    //     }

    //     return $this;
    // }
}
