<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $stockQuantity = null;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: categories::class)]
    private Collection $categorieId;

    #[ORM\ManyToMany(targetEntity: TransactionIteme::class, mappedBy: 'product_id')]
    private Collection $transactionItemes;

    #[ORM\ManyToMany(targetEntity: Transactions::class, mappedBy: 'products')]
    private Collection $transactions;

    #[ORM\Column(length: 255)]
    private ?string $barCode = null;

    public function __construct()
    {
        $this->categorieId = new ArrayCollection();
        $this->transactionItemes = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStockQuantity(): ?int
    {
        return $this->stockQuantity;
    }

    public function setStockQuantity(int $stockQuantity): static
    {
        $this->stockQuantity = $stockQuantity;

        return $this;
    }

    /**
     * @return Collection<int, categories>
     */
    public function getCategorieId(): Collection
    {
        return $this->categorieId;
    }

    public function addCategorieId(categories $categorieId): static
    {
        if (!$this->categorieId->contains($categorieId)) {
            $this->categorieId->add($categorieId);
            $categorieId->setProducts($this);
        }

        return $this;
    }

    public function removeCategorieId(categories $categorieId): static
    {
        if ($this->categorieId->removeElement($categorieId)) {
            // set the owning side to null (unless already changed)
            if ($categorieId->getProducts() === $this) {
                $categorieId->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TransactionIteme>
     */
    public function getTransactionItemes(): Collection
    {
        return $this->transactionItemes;
    }

    public function addTransactionIteme(TransactionIteme $transactionIteme): static
    {
        if (!$this->transactionItemes->contains($transactionIteme)) {
            $this->transactionItemes->add($transactionIteme);
            $transactionIteme->addProductId($this);
        }

        return $this;
    }

    public function removeTransactionIteme(TransactionIteme $transactionIteme): static
    {
        if ($this->transactionItemes->removeElement($transactionIteme)) {
            $transactionIteme->removeProductId($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Transactions>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transactions $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->addProduct($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            $transaction->removeProduct($this);
        }

        return $this;
    }

    public function getBarCode(): ?string
    {
        return $this->barCode;
    }

    public function setBarCode(string $barCode): static
    {
        $this->barCode = $barCode;

        return $this;
    }
}
