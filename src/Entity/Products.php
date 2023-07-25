<?php

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
    private ?int $stock_quantity = null;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: categories::class)]
    private Collection $categorie_id;

    #[ORM\ManyToMany(targetEntity: TransactionIteme::class, mappedBy: 'product_id')]
    private Collection $transactionItemes;

    public function __construct()
    {
        $this->categorie_id = new ArrayCollection();
        $this->transactionItemes = new ArrayCollection();
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
        return $this->stock_quantity;
    }

    public function setStockQuantity(int $stock_quantity): static
    {
        $this->stock_quantity = $stock_quantity;

        return $this;
    }

    /**
     * @return Collection<int, categories>
     */
    public function getCategorieId(): Collection
    {
        return $this->categorie_id;
    }

    public function addCategorieId(categories $categorieId): static
    {
        if (!$this->categorie_id->contains($categorieId)) {
            $this->categorie_id->add($categorieId);
            $categorieId->setProducts($this);
        }

        return $this;
    }

    public function removeCategorieId(categories $categorieId): static
    {
        if ($this->categorie_id->removeElement($categorieId)) {
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
}
