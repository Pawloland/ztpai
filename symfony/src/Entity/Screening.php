<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\ScreeningRepository;
use App\State\ScreeningStateProcessorPOST;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            security: "is_granted('WORKER', object)",
            processor: ScreeningStateProcessorPOST::class,
        ),
        new Delete(
            security: "is_granted('WORKER_RemoveScreening', object)",
        ),
    ],
    normalizationContext: ['groups' => ['Screening:read']],
    denormalizationContext: ['groups' => ['Screening:write']],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: ['movie.id_movie' => 'exact'])
]
#[ApiFilter(
    OrderFilter::class,
    properties: ['start_time'],
)]
#[ApiFilter(
    DateFilter::class,
    properties: ['start_time' => 'strictly_after']
)]
#[ORM\Entity(repositoryClass: ScreeningRepository::class)]
#[ORM\Table(name: "screening")]
#[ORM\Index(name: "fk_screening_movie", columns: ["id_movie"])]
#[ORM\Index(name: "fk_screening_hall", columns: ["id_hall"])]
#[ORM\Index(name: "fk_screening_screening_type", columns: ["id_screening_type"])]
class Screening
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups(['Screening:read', 'Reservation:read'])]
    private int $id_screening;

    #[ORM\Column(type: "datetimetz", nullable: false)]
    #[Groups(['Screening:read', 'Screening:write', 'Reservation:read'])]
    private DateTimeInterface $start_time;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: "screening")]
    #[Groups(['Screening:read'])]
    private iterable $reservation;

    #[ORM\ManyToOne(targetEntity: Hall::class, inversedBy: "screenings")]
    #[ORM\JoinColumn(name: "id_hall",
        referencedColumnName: "id_hall",
        nullable: false,
        onDelete: "RESTRICT")]
    #[Groups(['Screening:read', 'Screening:write', 'Reservation:read'])]
    private Hall $hall;

    #[ORM\ManyToOne(targetEntity: Movie::class, inversedBy: "screenings")]
    #[ORM\JoinColumn(name: "id_movie",
        referencedColumnName: "id_movie",
        nullable: false,
        onDelete: "RESTRICT")]
    #[Groups(['Screening:read', 'Screening:write', 'Reservation:read'])]
    private Movie $movie;

    #[ORM\ManyToOne(targetEntity: ScreeningType::class, inversedBy: "screenings")]
    #[ORM\JoinColumn(name: "id_screening_type",
        referencedColumnName: "id_screening_type",
        nullable: false,
        onDelete: "RESTRICT")]
    #[Groups(['Screening:read', 'Screening:write', 'Reservation:read'])]
    private ScreeningType $screeningType;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

    public function getIdScreening(): int
    {
        return $this->id_screening;
    }

    public function setIdScreening(int $id_screening): void
    {
        $this->id_screening = $id_screening;
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

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }

    public function getScreeningType(): ?ScreeningType
    {
        return $this->screeningType;
    }

    public function setScreeningType(?ScreeningType $screeningType): static
    {
        $this->screeningType = $screeningType;

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
            $reservation->setScreening($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
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