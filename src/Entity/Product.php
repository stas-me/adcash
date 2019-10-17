<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $discount_from_quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $discount_percent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDiscountFromQuantity(): ?int
    {
        return $this->discount_from_quantity;
    }

    public function setDiscountFromQuantity(int $discount_from_quantity): self
    {
        $this->discount_from_quantity = $discount_from_quantity;

        return $this;
    }

    public function getDiscountPercent(): ?float
    {
        return $this->discount_percent;
    }

    public function setDiscountPercent(float $discount_percent): self
    {
        $this->discount_percent = $discount_percent;

        return $this;
    }
}
