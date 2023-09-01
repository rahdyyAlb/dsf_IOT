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
	private ?float $unitPrice = null;

	#[ORM\Column]
	private ?int $stockQuantity = null;

	#[ORM\OneToMany(mappedBy: 'products', targetEntity: Categories::class)]
	private Collection $categorieId;

	#[ORM\Column(length: 255)]
	private ?string $barCode = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $img = null;

	#[ORM\OneToMany(mappedBy: 'cart', targetEntity: Panier::class)]
	private Collection $paniers;

	#[ORM\OneToMany(mappedBy: 'product', targetEntity: TransactionsProducts::class)]
	private Collection $transactionsProducts;

	public function __construct ()
	{
		$this->categorieId = new ArrayCollection();
		$this->transactionItemes = new ArrayCollection();
		$this->transactions = new ArrayCollection();
		$this->paniers = new ArrayCollection();
		$this->transactionsProducts = new ArrayCollection();
	}

	public function getId (): ?int
	{
		return $this->id;
	}

	public function getName (): ?string
	{
		return $this->name;
	}

	public function setName (string $name): static
	{
		$this->name = $name;

		return $this;
	}

	public function getUnitPrice (): ?float
	{
		return $this->unitPrice;
	}

	public function setUnitPrice (float $unitPrice): static
	{
		$this->unitPrice = $unitPrice;

		return $this;
	}

	public function getStockQuantity (): ?int
	{
		return $this->stockQuantity;
	}

	public function setStockQuantity (int $stockQuantity): static
	{
		$this->stockQuantity = $stockQuantity;

		return $this;
	}

	/**
	 * @return Collection<int, categories>
	 */
	public function getCategorieId (): Collection
	{
		return $this->categorieId;
	}

	public function addCategorieId (categories $categorieId): static
	{
		if (!$this->categorieId->contains($categorieId)) {
			$this->categorieId->add($categorieId);
			$categorieId->setProducts($this);
		}

		return $this;
	}

	public function removeCategorieId (categories $categorieId): static
	{
		if ($this->categorieId->removeElement($categorieId)) {
			// set the owning side to null (unless already changed)
			if ($categorieId->getProducts() === $this) {
				$categorieId->setProducts(null);
			}
		}

		return $this;
	}

	public function getBarCode (): ?string
	{
		return $this->barCode;
	}

	public function setBarCode (string $barCode): static
	{
		$this->barCode = $barCode;

		return $this;
	}

	public function getImg (): ?string
	{
		return $this->img;
	}

	public function setImg (?string $img): static
	{
		$this->img = $img;

		return $this;
	}

	/**
	 * @return Collection<int, Panier>
	 */
	public function getPaniers (): Collection
	{
		return $this->paniers;
	}

	public function addPanier (Panier $panier): static
	{
		if (!$this->paniers->contains($panier)) {
			$this->paniers->add($panier);
			$panier->setProducts($this);
		}

		return $this;
	}

	public function removePanier (Panier $panier): static
	{
		if ($this->paniers->removeElement($panier)) {
			// set the owning side to null (unless already changed)
			if ($panier->getProducts() === $this) {
				$panier->setProducts(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, TransactionsProducts>
	 */
	public function getTransactionsProducts (): Collection
	{
		return $this->transactionsProducts;
	}

	public function addTransactionsProduct (TransactionsProducts $transactionsProduct): static
	{
		if (!$this->transactionsProducts->contains($transactionsProduct)) {
			$this->transactionsProducts->add($transactionsProduct);
			$transactionsProduct->setProduct($this);
		}

		return $this;
	}

	public function removeTransactionsProduct (TransactionsProducts $transactionsProduct): static
	{
		if ($this->transactionsProducts->removeElement($transactionsProduct)) {
			// set the owning side to null (unless already changed)
			if ($transactionsProduct->getProduct() === $this) {
				$transactionsProduct->setProduct(null);
			}
		}

		return $this;
	}
}
