<?php

namespace App\Document;

use App\Repository\DriverRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: DriverRepository::class)]
class Driver
{
    #[MongoDB\Id(type: "string", strategy: "none")]
    private string $number;

    #[MongoDB\Field(type: "string")]
    private string $fullName;

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }
}
