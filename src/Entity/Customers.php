<?php

namespace App\Entity;

use App\Repository\CustomersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomersRepository::class)]
class Customers
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $name = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $email = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $phone = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $address = null;

	#[ORM\ManyToOne(inversedBy: 'custumer_id')]
	private ?Transactions $transactions = null;

	public function getId (): ?int
	{
		return $this->id;
	}

	public function getName (): ?string
	{
		return $this->name;
	}

	public function setName (?string $name): static
	{
		$this->name = $name;

		return $this;
	}

	public function getEmail (): ?string
	{
		return $this->email;
	}

	public function setEmail (?string $email): static
	{
		$this->email = $email;

		return $this;
	}

	public function getPhone (): ?string
	{
		return $this->phone;
	}

	public function setPhone (?string $phone): static
	{
		$this->phone = $phone;

		return $this;
	}

	public function getAddress (): ?string
	{
		return $this->address;
	}

	public function setAddress (?string $address): static
	{
		$this->address = $address;

		return $this;
	}

	public function getTransactions (): ?Transactions
	{
		return $this->transactions;
	}

	public function setTransactions (?Transactions $transactions): static
	{
		$this->transactions = $transactions;

		return $this;
	}
}
