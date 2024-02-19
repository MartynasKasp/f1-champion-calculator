<?php

namespace App\Document;

use App\Repository\PredictionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: PredictionRepository::class)]
class Prediction
{
    #[MongoDB\Id(type: "string", strategy: "UUID")]
    protected ?string $id = null;

    #[MongoDB\Field(type: "string")]
    protected string $driverId;

    // protected string $raceId;

    #[MongoDB\EmbedMany(strategy: "addToSet", targetDocument: PredictionComparison::class)]
    protected Collection $comparisons;

    public function __construct()
    {
        $this->setComparisons(new ArrayCollection());
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setDriverId(string $driverId): self
    {
        $this->driverId = $driverId;
        return $this;
    }

    public function getDriverId(): string
    {
        return $this->driverId;
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
