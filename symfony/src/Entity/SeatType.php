<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SeatTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['SeatType:read']],
    denormalizationContext: ['groups' => ['SeatType:write']]
)]
#[ORM\Entity(repositoryClass: SeatTypeRepository::class)]
#[ORM\Table(name: "seat_type")]
#[ORM\UniqueConstraint(name: "seat_name", columns: ["seat_name"])]
class SeatType
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups(['Hall:read', 'SeatType:read'])]
    private int $id_seat_type;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    #[Groups(['SeatType:read'])]
    private string $seat_name;

    #[ORM\Column(type: "decimal", nullable: false, scale: 2)]
    #[Groups(['SeatType:read'])]
    private string $price;

    #[ORM\OneToMany(targetEntity: Seat::class, mappedBy: "seatType")]
    #[Groups(['SeatType:read'])]
    private iterable $seats;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
    }

    public function getIdSeatType(): int
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
     * @return Collection<int, Seat>
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): static
    {
        if (!$this->seats->contains($seat)) {
            $this->seats->add($seat);
            $seat->setSeatType($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): static
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