<?php

namespace App\Entity;

use App\Repository\MovieGenreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieGenreRepository::class)]
#[ORM\Table(name: "movie_genre")]
#[ORM\Index(name: "fk_movie_genre_genre", columns: ["id_genre"])]
class movie_genre
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: \movie::class, inversedBy: "movieGenres")]
    #[ORM\JoinColumn(name: "id_movie",
            referencedColumnName: "id_movie",
            nullable: false,
            onDelete: "RESTRICT")]
    private $movie;

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: \genre::class, inversedBy: "movieGenres")]
    #[ORM\JoinColumn(name: "id_genre",
            referencedColumnName: "id_genre",
            nullable: false,
            onDelete: "RESTRICT")]
    private $genre;

    public function getMovie(): ?movie
    {
        return $this->movie;
    }

    public function setMovie(movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }

    public function getGenre(): ?genre
    {
        return $this->genre;
    }

    public function setGenre(genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }
}