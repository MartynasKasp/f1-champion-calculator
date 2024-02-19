<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: TeamRepository::class)]
class Team
{
    #[MongoDB\Id(type: "string", strategy: "none")]
    private string $id;

    #[MongoDB\Field(type: "string")]
    private string $name;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
