<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TransactionsProductsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionsProductsRepository::class)]
class TransactionsProducts
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'transactionsProducts')]
	private ?Transactions $transaction = null;

	#[ORM\ManyToOne(inversedBy: 'transactionsProducts')]
	private ?Products $product = null;

	#[ORM\Column]
	private ?int $quantity = null;

	#[ORM\Column]
	private ?int $price = null;

	public function getId (): ?int
	{
		return $this->id;
	}

	public function getTransaction (): ?Transactions
	{
		return $this->transaction;
	}

	public function setTransaction (?Transactions $transaction): static
	{
		$this->transaction = $transaction;

		return $this;
	}

	public function getProduct (): ?Products
	{
		return $this->product;
	}

	public function setProduct (?Products $product): static
	{
		$this->product = $product;

		return $this;
	}

	public function getQuantity (): ?int
	{
		return $this->quantity;
	}

	public function setQuantity (int $quantity): static
	{
		$this->quantity = $quantity;

		return $this;
	}

	public function getPrice (): ?int
	{
		return $this->price;
	}

	public function setPrice (int $price): static
	{
		$this->price = $price;

		return $this;
	}
}
