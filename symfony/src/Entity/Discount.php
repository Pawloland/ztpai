<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DiscountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    normalizationContext: ['groups' => ['Discount:read']],
    denormalizationContext: ['groups' => ['Discount:write']]
)]
#[ORM\Entity(repositoryClass: DiscountRepository::class)]
#[ORM\Table(name: "discount")]
#[ORM\UniqueConstraint(name: "discount_discount_name_key", columns: ["discount_name"])]
class Discount
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id_discount;

    #[ORM\Column(type: "string", length: 20, nullable: false)]
    private string $discount_name;

    #[ORM\Column(type: "decimal", nullable: false, scale: 2)]
    private string $amount;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: "discount")]
    private iterable $reservations;

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
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setDiscount($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
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
