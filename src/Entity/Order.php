<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Range(min=1, minMessage="You should order at least 1 item.")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", fetch="EAGER", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="App\Entity\User", fetch="EAGER", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function getDateString(): string
    {
        return $this->getDate()->format('Y-m-d H:i:s');
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function calculateOrder(): void
    {
        $prod = $this->getProduct();
        $total = $prod->getPrice() * $this->getQuantity();
        if( $prod->getDiscountFromQuantity() > 0 && $prod->getDiscountFromQuantity() <= $this->getQuantity() ){
            $total *= (100 - $prod->getDiscountPercent());
            $total /= 100;
        }

        $this->setTotal($total);

        if( !$this->getId() ){
            $this->setDate( new \DateTime('now') );
        }
    }
}
