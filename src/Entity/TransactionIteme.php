<?php

declare(strict_types=1);

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

    #[ORM\ManyToMany(targetEntity: Products::class, inversedBy: 'transactionItemes')]
    private Collection $productId;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\ManyToMany(targetEntity: Transactions::class, inversedBy: 'transactionItemes')]
    private Collection $transactionId;

    public function __construct()
    {
        $this->transactionId = new ArrayCollection();
        $this->productId = new ArrayCollection();
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
     * @return Collection<int, Products>
     */
    public function getProductId(): Collection
    {
        return $this->productId;
    }

    public function addProductId(Products $productId): static
    {
        if (!$this->productId->contains($productId)) {
            $this->productId->add($productId);
        }

        return $this;
    }

    public function removeProductId(Products $productId): static
    {
        $this->productId->removeElement($productId);

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

    /**
     * @return Collection<int, Transactions>
     */
    public function getTransactionId(): Collection
    {
        return $this->transactionId;
    }

    public function addTransactionId(Transactions $transactionId): static
    {
        if (!$this->transactionId->contains($transactionId)) {
            $this->transactionId->add($transactionId);
        }

        return $this;
    }

    public function removeTransactionId(Transactions $transactionId): static
    {
        $this->transactionId->removeElement($transactionId);

        return $this;
    }
}
