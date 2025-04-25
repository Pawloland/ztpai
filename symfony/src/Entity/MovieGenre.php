<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieGenreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['MovieGenre:read']],
    denormalizationContext: ['groups' => ['MovieGenre:write']]
)]
#[ORM\Entity(repositoryClass: MovieGenreRepository::class)]
#[ORM\Table(name: "movie_genre")]
#[ORM\Index(name: "fk_movie_genre_genre", columns: ["id_genre"])]
class MovieGenre
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Movie::class, inversedBy: "movieGenres")]
    #[ORM\JoinColumn(name: "id_movie",
            referencedColumnName: "id_movie",
            nullable: false,
            onDelete: "RESTRICT")]
    #[Groups(['MovieGenre:read'])]
    private Movie $movie;

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Genre::class, inversedBy: "movieGenres")]
    #[ORM\JoinColumn(name: "id_genre",
            referencedColumnName: "id_genre",
            nullable: false,
            onDelete: "RESTRICT")]
    #[Groups(['MovieGenre:read'])]
    private Genre $genre;

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(Genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }
}