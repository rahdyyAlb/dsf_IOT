<?php

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
    private ?float $cash_total = null;

    #[ORM\Column]
    private ?float $card_total = null;

    #[ORM\Column]
    private ?float $cheque_total = null;

	#[ORM\OneToMany(mappedBy: 'caisse', targetEntity: Caisse::class)]
	private Collection $caisse_id;

	public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCashTotal(): ?float
    {
        return $this->cash_total;
    }

    public function setCashTotal(float $cash_total): static
    {
        $this->cash_total = $cash_total;

        return $this;
    }

    public function getCardTotal(): ?float
    {
        return $this->card_total;
    }

    public function setCardTotal(float $card_total): static
    {
        $this->card_total = $card_total;

        return $this;
    }

    public function getChequeTotal(): ?float
    {
        return $this->cheque_total;
    }

    public function setChequeTotal(float $cheque_total): static
    {
        $this->cheque_total = $cheque_total;

        return $this;
    }
}
