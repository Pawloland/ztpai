<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeatRepository::class)]
#[ORM\Table(name: "seat")]
#[ORM\Index(name: "fk_seat_hall", columns: ["id_hall"])]
#[ORM\Index(name: "fk_seat_seat_type", columns: ["id_seat_type"])]
class seat
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_seat;

    #[ORM\Column(type: "string", length: 2, nullable: false)]
    private $row;

    #[ORM\Column(type: "integer", nullable: false)]
    private $number;

    #[ORM\OneToMany(targetEntity: \reservation::class, mappedBy: "seat")]
    private $reservation;

    #[ORM\ManyToOne(targetEntity: \hall::class, inversedBy: "seats")]
    #[ORM\JoinColumn(name: "id_hall",
            referencedColumnName: "id_hall",
            nullable: false,
            onDelete: "RESTRICT")]
    private $hall;

    #[ORM\ManyToOne(targetEntity: \seat_type::class, inversedBy: "seats")]
    #[ORM\JoinColumn(name: "id_seat_type",
            referencedColumnName: "id_seat_type",
            nullable: false,
            onDelete: "RESTRICT")]
    private $seatType;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

    public function getIdSeat(): ?int
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

    public function getHall(): ?hall
    {
        return $this->hall;
    }

    public function setHall(?hall $hall): static
    {
        $this->hall = $hall;

        return $this;
    }

    public function getSeatType(): ?seat_type
    {
        return $this->seatType;
    }

    public function setSeatType(?seat_type $seatType): static
    {
        $this->seatType = $seatType;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(reservation $reservation): static
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setSeat($this);
        }

        return $this;
    }

    public function removeReservation(reservation $reservation): static
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