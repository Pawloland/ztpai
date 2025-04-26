<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GenreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['Genre:read']],
    denormalizationContext: ['groups' => ['Genre:write']]
)]
#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ORM\Table(name: "genre")]
#[ORM\UniqueConstraint(name: "genre_genre_name_key", columns: ["genre_name"])]
class Genre
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups(['Genre:read'])]
    private int $id_genre;


    #[ORM\Column(type: "string", length: 40, nullable: false)]
    #[Groups(['Genre:read'])]
    private string $genre_name;

    #[ORM\OneToOne(targetEntity: MovieGenre::class, mappedBy: "genre")]
    private MovieGenre $movieGenres;

    public function getIdGenre(): int
    {
        return $this->id_genre;
    }

    public function getGenreName(): ?string
    {
        return $this->genre_name;
    }

    public function setGenreName(string $genre_name): static
    {
        $this->genre_name = $genre_name;

        return $this;
    }

    public function getMovieGenres(): ?MovieGenre
    {
        return $this->movieGenres;
    }

    public function setMovieGenres(?MovieGenre $movieGenres): static
    {
        // unset the owning side of the relation if necessary
        if ($movieGenres === null && $this->movieGenres !== null) {
            $this->movieGenres->setGenre(null);
        }

        // set the owning side of the relation if necessary
        if ($movieGenres !== null && $movieGenres->getGenre() !== $this) {
            $movieGenres->setGenre($this);
        }

        $this->movieGenres = $movieGenres;

        return $this;
    }
}