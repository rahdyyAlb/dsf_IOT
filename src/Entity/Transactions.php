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
    private Collection $custumerId;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $transactionsDate = null;

    #[ORM\Column]
    private ?float $totalAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $cashAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $cardAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $chequeAmount = null;

    public function __construct()
    {
        $this->custumerId = new ArrayCollection();
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
        $this->transactionsDate = $transactionsDate;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
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
}
