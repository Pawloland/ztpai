<?php

namespace App\Entity;

use App\Repository\ScreeningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScreeningRepository::class)]
#[ORM\Table(name: "screening")]
#[ORM\Index(name: "fk_screening_movie", columns: ["id_movie"])]
#[ORM\Index(name: "fk_screening_hall", columns: ["id_hall"])]
#[ORM\Index(name: "fk_screening_screening_type", columns: ["id_screening_type"])]
class screening
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_screening;

    #[ORM\Column(type: "datetimetz", nullable: true)]
    private $start_time;

    #[ORM\OneToMany(targetEntity: \reservation::class, mappedBy: "screening")]
    private $reservation;

    #[ORM\ManyToOne(targetEntity: \hall::class, inversedBy: "screenings")]
    #[ORM\JoinColumn(name: "id_hall",
            referencedColumnName: "id_hall",
            nullable: false,
            onDelete: "RESTRICT")]
    private $hall;

    #[ORM\ManyToOne(targetEntity: \movie::class, inversedBy: "screenings")]
    #[ORM\JoinColumn(name: "id_movie",
            referencedColumnName: "id_movie",
            nullable: false,
            onDelete: "RESTRICT")]
    private $movie;

    #[ORM\ManyToOne(targetEntity: \screening_type::class, inversedBy: "screenings")]
    #[ORM\JoinColumn(name: "id_screening_type",
            referencedColumnName: "id_screening_type",
            nullable: false,
            onDelete: "RESTRICT")]
    private $screeningType;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

    public function getIdScreening(): ?int
    {
        return $this->id_screening;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(?\DateTimeInterface $start_time): static
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getMovie(): ?movie
    {
        return $this->movie;
    }

    public function setMovie(?movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }

    public function getScreeningType(): ?screening_type
    {
        return $this->screeningType;
    }

    public function setScreeningType(?screening_type $screeningType): static
    {
        $this->screeningType = $screeningType;

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
            $reservation->setScreening($this);
        }

        return $this;
    }

    public function removeReservation(reservation $reservation): static
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getScreening() === $this) {
                $reservation->setScreening(null);
            }
        }

        return $this;
    }
}