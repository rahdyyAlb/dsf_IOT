<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TransactionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionsRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Transactions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'transactions', targetEntity: Customers::class)]
    private Collection $custumerId;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $transactionsDate = null;

    #[ORM\Column]
    private ?float $totalAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $cashAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $cardAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $chequeAmount = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?Caisse $caisse = null;

    #[ORM\OneToMany(mappedBy: 'transaction', targetEntity: TransactionsProducts::class, cascade: ['persist'])]
    private Collection $transactionsProducts;

    public function __construct()
    {
        $this->custumerId = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->transactionsProducts = new ArrayCollection();
    }

    /**
     * @return Collection<int, Customers>
     */
    public function getCustumerId(): Collection
    {
        return $this->custumerId;
    }

    public function addCustumerId(Customers $custumerId): static
    {
        if (!$this->custumerId->contains($custumerId)) {
            $this->custumerId->add($custumerId);
            $custumerId->setTransactions($this);
        }

        return $this;
    }

    public function removeCustumerId(Customers $custumerId): static
    {
        if ($this->custumerId->removeElement($custumerId)) {
            // set the owning side to null (unless already changed)
            if ($custumerId->getTransactions() === $this) {
                $custumerId->setTransactions(null);
            }
        }

        return $this;
    }

    public function getTransactionsDate(): ?\DateTimeInterface
    {
        return $this->transactionsDate;
    }

    public function setTransactionsDate(\DateTimeInterface $transactionsDate): static
    {
        if ($transactionsDate !== null) {
            $this->transactionsDate = $transactionsDate;
        }
        $this->transactionsDate = new \DateTime('now');

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?float $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * @return Collection<int, TransactionsProducts>
     */
    public function getTransactionsProducts(): Collection
    {
        return $this->transactionsProducts;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function getCardAmount(): ?float
    {
        return $this->cardAmount;
    }

    public function setCardAmount(?float $cardAmount): static
    {
        $this->cardAmount = $cardAmount;

        return $this;
    }

    public function getChequeAmount(): ?float
    {
        return $this->chequeAmount;
    }

    public function setChequeAmount(?float $chequeAmount): static
    {
        $this->chequeAmount = $chequeAmount;

        return $this;
    }

    public function calculateCashAmount(): float
    {
        $cashAmount = 0.0;

        foreach ($this->custumerId as $customer) {
            // Assuming the method for getting cash amount from the customer entity is 'getCashAmount()'
            $customerCashAmount = $customer->getCashAmount();
            if ($customerCashAmount !== null) {
                $cashAmount += $customerCashAmount;
            }
        }

        return $cashAmount;
    }

    public function getCashAmount(): ?float
    {
        return $this->cashAmount;
    }

    public function setCashAmount(?float $cashAmount): static
    {
        $this->cashAmount = $cashAmount;

        return $this;
    }

    public function getCaisse(): ?Caisse
    {
        return $this->caisse;
    }

    public function setCaisse(?Caisse $caisse): static
    {
        $this->caisse = $caisse;

        return $this;
    }

    public function addProduct(Products $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(Products $product): static
    {
        $this->products->removeElement($product);

        return $this;
    }

    public function addProductWithQuantity(Products $product, int $quantity): self
    {
        $transactionProduct = new TransactionsProducts();
        $transactionProduct->setTransaction($this);
        $transactionProduct->setProduct($product);
        $transactionProduct->setQuantity($quantity);

        $this->addTransactionsProduct($transactionProduct);

        return $this;
    }

    public function addTransactionsProduct(TransactionsProducts $transactionsProduct): static
    {
        if (!$this->transactionsProducts->contains($transactionsProduct)) {
            $this->transactionsProducts->add($transactionsProduct);
            $transactionsProduct->setTransaction($this);
        }

        return $this;
    }

    public function removeTransactionsProduct(TransactionsProducts $transactionsProduct): static
    {
        if ($this->transactionsProducts->removeElement($transactionsProduct)) {
            // set the owning side to null (unless already changed)
            if ($transactionsProduct->getTransaction() === $this) {
                $transactionsProduct->setTransaction(null);
            }
        }

        return $this;
    }

    public function calculateItemTotals(): array
    {
        $itemTotals = [];

        /** @var TransactionsProducts $transactionProduct */
        foreach ($this->getTransactionsProducts() as $transactionProduct) {
            $product = $transactionProduct->getProduct();
            $quantity = $transactionProduct->getQuantity();
            $price = $product->getUnitPrice();
            $itemTotal = $quantity * $price;

            $itemTotals[$product->getId()] = $itemTotal;
        }

        return $itemTotals;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
