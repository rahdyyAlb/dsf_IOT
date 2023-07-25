<?php

namespace App\Entity;

use App\Repository\TransactionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionsRepository::class)]
class Transactions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'transactions', targetEntity: Customers::class)]
    private Collection $custumer_id;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $transactions_date = null;

    #[ORM\Column]
    private ?float $total_amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $cash_amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $card_amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $cheque_amount = null;

    public function __construct()
    {
        $this->custumer_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Customers>
     */
    public function getCustumerId(): Collection
    {
        return $this->custumer_id;
    }

    public function addCustumerId(Customers $custumerId): static
    {
        if (!$this->custumer_id->contains($custumerId)) {
            $this->custumer_id->add($custumerId);
            $custumerId->setTransactions($this);
        }

        return $this;
    }

    public function removeCustumerId(Customers $custumerId): static
    {
        if ($this->custumer_id->removeElement($custumerId)) {
            // set the owning side to null (unless already changed)
            if ($custumerId->getTransactions() === $this) {
                $custumerId->setTransactions(null);
            }
        }

        return $this;
    }

    public function getTransactionsDate(): ?\DateTimeInterface
    {
        return $this->transactions_date;
    }

    public function setTransactionsDate(\DateTimeInterface $transactions_date): static
    {
        $this->transactions_date = $transactions_date;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->total_amount;
    }

    public function setTotalAmount(float $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getCashAmount(): ?float
    {
        return $this->cash_amount;
    }

    public function setCashAmount(?float $cash_amount): static
    {
        $this->cash_amount = $cash_amount;

        return $this;
    }

    public function getCardAmount(): ?float
    {
        return $this->card_amount;
    }

    public function setCardAmount(?float $card_amount): static
    {
        $this->card_amount = $card_amount;

        return $this;
    }

    public function getChequeAmount(): ?float
    {
        return $this->cheque_amount;
    }

    public function setChequeAmount(?float $cheque_amount): static
    {
        $this->cheque_amount = $cheque_amount;

        return $this;
    }
}
