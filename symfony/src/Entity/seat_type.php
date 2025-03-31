<?php

namespace App\Entity;

use App\Repository\SeatTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeatTypeRepository::class)]
#[ORM\Table(name: "seat_type")]
#[ORM\UniqueConstraint(name: "seat_name", columns: ["seat_name"])]
class seat_type
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_seat_type;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    private $seat_name;

    #[ORM\Column(type: "decimal", nullable: false, scale: 2)]
    private $price;

    #[ORM\OneToMany(targetEntity: \seat::class, mappedBy: "seatType")]
    private $seats;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
    }

    public function getIdSeatType(): ?int
    {
        return $this->id_seat_type;
    }

    public function getSeatName(): ?string
    {
        return $this->seat_name;
    }

    public function setSeatName(string $seat_name): static
    {
        $this->seat_name = $seat_name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, seat>
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(seat $seat): static
    {
        if (!$this->seats->contains($seat)) {
            $this->seats->add($seat);
            $seat->setSeatType($this);
        }

        return $this;
    }

    public function removeSeat(seat $seat): static
    {
        if ($this->seats->removeElement($seat)) {
            // set the owning side to null (unless already changed)
            if ($seat->getSeatType() === $this) {
                $seat->setSeatType(null);
            }
        }

        return $this;
    }
}