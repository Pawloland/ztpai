<?php

namespace App\Entity;

use App\Repository\ScreeningTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScreeningTypeRepository::class)]
#[ORM\Table(name: "screening_type")]
#[ORM\UniqueConstraint(name: "screening_name", columns: ["screening_name"])]
class screening_type
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_screening_type;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    private $screening_name;

    #[ORM\Column(type: "decimal", nullable: false, scale: 2)]
    private $price;

    #[ORM\OneToMany(targetEntity: \screening::class, mappedBy: "screeningType")]
    private $screenings;

    public function __construct()
    {
        $this->screenings = new ArrayCollection();
    }

    public function getIdScreeningType(): ?int
    {
        return $this->id_screening_type;
    }

    public function getScreeningName(): ?string
    {
        return $this->screening_name;
    }

    public function setScreeningName(string $screening_name): static
    {
        $this->screening_name = $screening_name;

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
            $screening->setScreeningType($this);
        }

        return $this;
    }

    public function removeScreening(screening $screening): static
    {
        if ($this->screenings->removeElement($screening)) {
            // set the owning side to null (unless already changed)
            if ($screening->getScreeningType() === $this) {
                $screening->setScreeningType(null);
            }
        }

        return $this;
    }
}