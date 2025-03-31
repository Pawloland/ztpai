<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ORM\Table(name: "genre")]
#[ORM\UniqueConstraint(name: "genre_genre_name_key", columns: ["genre_name"])]
class genre
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_genre;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    private $genre_name;

    #[ORM\OneToOne(targetEntity: \movie_genre::class, mappedBy: "genre")]
    private $movieGenres;

    public function getIdGenre(): ?int
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

    public function getMovieGenres(): ?movie_genre
    {
        return $this->movieGenres;
    }

    public function setMovieGenres(?movie_genre $movieGenres): static
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