<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DayRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DayRepository::class)]
class Day
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(type: Types::DATE_MUTABLE)]
	private ?\DateTimeInterface $date = null;

	#[ORM\Column]
	private ?float $cashTotal = null;

	#[ORM\Column]
	private ?float $cardTotal = null;

	#[ORM\Column]
	private ?float $chequeTotal = null;

	#[ORM\ManyToOne(inversedBy: 'days', targetEntity: Caisse::class)]
	private ?caisse $caisseId = null;

	public function getId (): ?int
	{
		return $this->id;
	}

	public function getDate (): ?\DateTimeInterface
	{
		return $this->date;
	}

	public function setDate (\DateTimeInterface $date): static
	{
		$this->date = $date;

		return $this;
	}

	public function getCashTotal (): ?float
	{
		return $this->cashTotal;
	}

	public function setCashTotal (float $cashTotal): static
	{
		$this->cashTotal = $cashTotal;

		return $this;
	}

	public function getCardTotal (): ?float
	{
		return $this->cardTotal;
	}

	public function setCardTotal (float $cardTotal): static
	{
		$this->cardTotal = $cardTotal;

		return $this;
	}

	public function getChequeTotal (): ?float
	{
		return $this->chequeTotal;
	}

	public function setChequeTotal (float $chequeTotal): static
	{
		$this->chequeTotal = $chequeTotal;

		return $this;
	}

	public function getCaisseId (): ?caisse
	{
		return $this->caisseId;
	}

	public function setCaisseId (?caisse $caisseId): static
	{
		$this->caisseId = $caisseId;

		return $this;
	}
}
