<?php

namespace App\Entity;

use App\Repository\TransactionItemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionItemeRepository::class)]
class TransactionIteme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'transaction_id')]
    private ?self $transactionIteme = null;

    #[ORM\OneToMany(mappedBy: 'transactionIteme', targetEntity: self::class)]
    private Collection $transaction_id;

    #[ORM\ManyToMany(targetEntity: Products::class, inversedBy: 'transactionItemes')]
    private Collection $product_id;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $total = null;

    public function __construct()
    {
        $this->transaction_id = new ArrayCollection();
        $this->product_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionIteme(): ?self
    {
        return $this->transactionIteme;
    }

    public function setTransactionIteme(?self $transactionIteme): static
    {
        $this->transactionIteme = $transactionIteme;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTransactionId(): Collection
    {
        return $this->transaction_id;
    }

    public function addTransactionId(self $transactionId): static
    {
        if (!$this->transaction_id->contains($transactionId)) {
            $this->transaction_id->add($transactionId);
            $transactionId->setTransactionIteme($this);
        }

        return $this;
    }

    public function removeTransactionId(self $transactionId): static
    {
        if ($this->transaction_id->removeElement($transactionId)) {
            // set the owning side to null (unless already changed)
            if ($transactionId->getTransactionIteme() === $this) {
                $transactionId->setTransactionIteme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getProductId(): Collection
    {
        return $this->product_id;
    }

    public function addProductId(Products $productId): static
    {
        if (!$this->product_id->contains($productId)) {
            $this->product_id->add($productId);
        }

        return $this;
    }

    public function removeProductId(Products $productId): static
    {
        $this->product_id->removeElement($productId);

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }
}
