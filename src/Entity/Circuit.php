<?php

namespace App\Entity;

use App\Repository\CircuitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CircuitRepository::class)]
class Circuit
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(type: "string")]
    protected ?string $id;

    #[ORM\Column(type: "string")]
    protected string $country;

    #[ORM\Column(type: "string")]
    protected string $circuit;

    #[ORM\Column(type: "string")]
    protected string $description = '';

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;
        return $this;
    }

    public function getCircuit(): string
    {
        return $this->circuit;
    }

    public function setCircuit(string $circuit): static
    {
        $this->circuit = $circuit;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }
}
