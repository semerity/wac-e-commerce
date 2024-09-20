<?php

namespace App\Entity;

use App\Repository\DeliveryMethodRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryMethodRepository::class)]
class DeliveryMethod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $mult = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMult(): ?float
    {
        return $this->mult;
    }

    public function setMult(float $mult): static
    {
        $this->mult = $mult;

        return $this;
    }
}
