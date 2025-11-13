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

    /** @var Collection<int,PredictionComparison> $comparisons */
    #[ORM\OneToMany(PredictionComparison::class, mappedBy: "prediction")]
    protected Collection $comparisons;

    #[ORM\ManyToOne]
    protected Race $race;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    protected ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->setComparisons(new ArrayCollection());
        $this->createdAt = new \DateTimeImmutable();
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

    /**
     * @return Collection<int,PredictionComparison>
     */
    public function getComparisons(): Collection
    {
        return $this->comparisons;
    }

    public function addComparison(PredictionComparison $comparison): static
    {
        $this->comparisons->add($comparison);
        $comparison->setPrediction($this);

        return $this;
    }

    /**
     * @param Collection<int,PredictionComparison> $comparisons
     */
    public function setComparisons(Collection $comparisons): static
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
}
