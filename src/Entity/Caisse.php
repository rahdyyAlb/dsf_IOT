<?php

namespace App\Entity;

use App\Repository\CaisseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaisseRepository::class)]
class Caisse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\OneToMany(mappedBy: 'caisse_id', targetEntity: Day::class)]
    private Collection $days;

    public function __construct()
    {
        $this->days = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, Day>
     */
    public function getDays(): Collection
    {
        return $this->days;
    }

    public function addDay(Day $day): static
    {
        if (!$this->days->contains($day)) {
            $this->days->add($day);
            $day->setCaisseId($this);
        }

        return $this;
    }

    public function removeDay(Day $day): static
    {
        if ($this->days->removeElement($day)) {
            // set the owning side to null (unless already changed)
            if ($day->getCaisseId() === $this) {
                $day->setCaisseId(null);
            }
        }

        return $this;
    }
}
