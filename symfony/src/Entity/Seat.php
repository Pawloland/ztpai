<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\SeatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['Seat:read']],
    denormalizationContext: ['groups' => ['Seat:write']]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: ['hall.id_hall' => 'exact'])
]
#[ORM\Entity(repositoryClass: SeatRepository::class)]
#[ORM\Table(name: "seat")]
#[ORM\Index(name: "fk_seat_hall", columns: ["id_hall"])]
#[ORM\Index(name: "fk_seat_seat_type", columns: ["id_seat_type"])]
class Seat
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups(['Seat:read', 'Reservation:read'])]
    private int $id_seat;

    #[ORM\Column(type: "string", length: 2, nullable: false)]
    #[Groups(['Seat:read','Reservation:read'])]
    private string $row;

    #[ORM\Column(type: "integer", nullable: false)]
    #[Groups(['Seat:read','Reservation:read'])]
    private int $number;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: "seat")]
    private iterable $reservation;

    #[ORM\ManyToOne(targetEntity: Hall::class, inversedBy: "seats")]
    #[ORM\JoinColumn(name: "id_hall",
        referencedColumnName: "id_hall",
        nullable: false,
        onDelete: "RESTRICT")]
    #[Groups(['Seat:read'])]
    private Hall $hall;

    #[ORM\ManyToOne(targetEntity: SeatType::class, inversedBy: "seats")]
    #[ORM\JoinColumn(name: "id_seat_type",
        referencedColumnName: "id_seat_type",
        nullable: false,
        onDelete: "RESTRICT")]
    #[Groups(['Hall:read', 'Seat:read', 'Reservation:read'])]
    private SeatType $seatType;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

    public function getIdSeat(): int
    {
        return $this->id_seat;
    }

    public function getRow(): ?string
    {
        return $this->row;
    }

    public function setRow(string $row): static
    {
        $this->row = $row;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getHall(): ?Hall
    {
        return $this->hall;
    }

    public function setHall(?Hall $hall): static
    {
        $this->hall = $hall;

        return $this;
    }

    public function getSeatType(): ?SeatType
    {
        return $this->seatType;
    }

    public function setSeatType(?SeatType $seatType): static
    {
        $this->seatType = $seatType;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setSeat($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSeat() === $this) {
                $reservation->setSeat(null);
            }
        }

        return $this;
    }
}