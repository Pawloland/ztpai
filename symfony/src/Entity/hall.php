<?php

namespace App\Entity;

use App\Repository\HallRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HallRepository::class)]
#[ORM\Table(name: "hall")]
#[ORM\UniqueConstraint(name: "hall_hall_name_key", columns: ["hall_name"])]
class hall
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_hall;

    #[ORM\Column(type: "string", length: 40, nullable: true)]
    private $hall_name;

    #[ORM\OneToMany(targetEntity: \screening::class, mappedBy: "hall")]
    private $screenings;

    #[ORM\OneToMany(targetEntity: \seat::class, mappedBy: "hall")]
    private $seats;

    public function __construct()
    {
        $this->screenings = new ArrayCollection();
        $this->seats = new ArrayCollection();
    }

    public function getIdHall(): ?int
    {
        return $this->id_hall;
    }

    public function getHallName(): ?string
    {
        return $this->hall_name;
    }

    public function setHallName(?string $hall_name): static
    {
        $this->hall_name = $hall_name;

        return $this;
    }

    /**
     * @return Collection<int, screening>
     */
    public function getScreenings(): Collection
    {
        return $this->screenings;
    }

    public function addScreening(screening $screening): static
    {
        if (!$this->screenings->contains($screening)) {
            $this->screenings->add($screening);
            $screening->setHall($this);
        }

        return $this;
    }

    public function removeScreening(screening $screening): static
    {
        if ($this->screenings->removeElement($screening)) {
            // set the owning side to null (unless already changed)
            if ($screening->getHall() === $this) {
                $screening->setHall(null);
            }
        }

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
            $seat->setHall($this);
        }

        return $this;
    }

    public function removeSeat(seat $seat): static
    {
        if ($this->seats->removeElement($seat)) {
            // set the owning side to null (unless already changed)
            if ($seat->getHall() === $this) {
                $seat->setHall(null);
            }
        }

        return $this;
    }
}