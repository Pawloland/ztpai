<?php

namespace App\Entity;

use App\Repository\DiscountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscountRepository::class)]
#[ORM\Table(name: "discount")]
#[ORM\UniqueConstraint(name: "discount_discount_name_key", columns: ["discount_name"])]
class discount
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_discount;

    #[ORM\Column(type: "string", length: 20, nullable: false)]
    private $discount_name;

    #[ORM\Column(type: "decimal", nullable: false, scale: 2)]
    private $amount;

    #[ORM\OneToMany(targetEntity: \reservation::class, mappedBy: "discount")]
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getIdDiscount(): ?int
    {
        return $this->id_discount;
    }

    public function getDiscountName(): ?string
    {
        return $this->discount_name;
    }

    public function setDiscountName(string $discount_name): static
    {
        $this->discount_name = $discount_name;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setDiscount($this);
        }

        return $this;
    }

    public function removeReservation(reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getDiscount() === $this) {
                $reservation->setDiscount(null);
            }
        }

        return $this;
    }
}
