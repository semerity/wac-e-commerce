<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
class Promotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_product = null;

    #[ORM\Column]
    private ?int $promotion = null;

    #[ORM\Column]
    private ?bool $is_discounted = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduct(): ?int
    {
        return $this->id_product;
    }

    public function setIdProduct(int $id_product): static
    {
        $this->id_product = $id_product;

        return $this;
    }

    public function getPromotion(): ?int
    {
        return $this->promotion;
    }

    public function setPromotion(int $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function isDiscounted(): ?bool
    {
        return $this->is_discounted;
    }

    public function setDiscounted(bool $is_discounted): static
    {
        $this->is_discounted = $is_discounted;

        return $this;
    }
}
