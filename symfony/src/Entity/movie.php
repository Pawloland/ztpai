<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\Table(name: "movie")]
#[ORM\Index(name: "fk_movie_language", columns: ["id_language"])]
#[ORM\Index(name: "fk_movie_dubbing", columns: ["id_dubbing"])]
#[ORM\Index(name: "fk_movie_subtitles", columns: ["id_subtitles"])]
class movie
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_movie;

    #[ORM\Column(type: "string", length: 80, nullable: false)]
    private $title;

    #[ORM\Column(type: "string", length: 80, nullable: false)]
    private $original_title;

    #[ORM\Column(type: "time", nullable: false)]
    private $duration;

    #[ORM\Column(type: "string", length: 500, nullable: true)]
    private $description;

    #[ORM\Column(type: "guid", nullable: true)]
    private $poster;

    #[ORM\OneToOne(targetEntity: \movie_genre::class, mappedBy: "movie")]
    private $movieGenres;

    #[ORM\OneToMany(targetEntity: \screening::class, mappedBy: "movie")]
    private $screenings;

    #[ORM\ManyToOne(targetEntity: \language::class, inversedBy: "movieViaIdLanguage")]
    #[ORM\JoinColumn(name: "id_language",
            referencedColumnName: "id_language",
            nullable: false,
            onDelete: "RESTRICT")]
    private $languageViaIdLanguage;

    #[ORM\ManyToOne(targetEntity: \language::class, inversedBy: "movieViaIdDubbing")]
    #[ORM\JoinColumn(name: "id_dubbing",
            referencedColumnName: "id_language",
            onDelete: "RESTRICT")]
    private $languageViaIdDubbing;

    #[ORM\ManyToOne(targetEntity: \language::class, inversedBy: "movieViaIdSubtitles")]
    #[ORM\JoinColumn(name: "id_subtitles",
            referencedColumnName: "id_language",
            onDelete: "RESTRICT")]
    private $languageViaIdSubtitles;

    public function __construct()
    {
        $this->screenings = new ArrayCollection();
    }

    public function getIdMovie(): ?int
    {
        return $this->id_movie;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getOriginalTitle(): ?string
    {
        return $this->original_title;
    }

    public function setOriginalTitle(string $original_title): static
    {
        $this->original_title = $original_title;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): static
    {
        $this->poster = $poster;

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
            $this->movieGenres->setMovie(null);
        }

        // set the owning side of the relation if necessary
        if ($movieGenres !== null && $movieGenres->getMovie() !== $this) {
            $movieGenres->setMovie($this);
        }

        $this->movieGenres = $movieGenres;

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
            $screening->setMovie($this);
        }

        return $this;
    }

    public function removeScreening(screening $screening): static
    {
        if ($this->screenings->removeElement($screening)) {
            // set the owning side to null (unless already changed)
            if ($screening->getMovie() === $this) {
                $screening->setMovie(null);
            }
        }

        return $this;
    }

    public function getLanguageViaIdLanguage(): ?language
    {
        return $this->languageViaIdLanguage;
    }

    public function setLanguageViaIdLanguage(?language $languageViaIdLanguage): static
    {
        $this->languageViaIdLanguage = $languageViaIdLanguage;

        return $this;
    }

    public function getLanguageViaIdDubbing(): ?language
    {
        return $this->languageViaIdDubbing;
    }

    public function setLanguageViaIdDubbing(?language $languageViaIdDubbing): static
    {
        $this->languageViaIdDubbing = $languageViaIdDubbing;

        return $this;
    }

    public function getLanguageViaIdSubtitles(): ?language
    {
        return $this->languageViaIdSubtitles;
    }

    public function setLanguageViaIdSubtitles(?language $languageViaIdSubtitles): static
    {
        $this->languageViaIdSubtitles = $languageViaIdSubtitles;

        return $this;
    }

    public function getIdLanguage(): ?int
    {
        return $this->languageViaIdLanguage ? $this->languageViaIdLanguage->getIdLanguage() : null;
    }

    public function setIdLanguage(?language $language): static
    {
        $this->languageViaIdLanguage = $language;

        return $this;
    }

    public function getIdDubbing(): ?int
    {
        return $this->languageViaIdDubbing ? $this->languageViaIdDubbing->getIdLanguage() : null;
    }

    public function setIdDubbing(?language $language): static
    {
        $this->languageViaIdDubbing = $language;

        return $this;
    }

    public function getIdSubtitles(): ?int
    {
        return $this->languageViaIdSubtitles ? $this->languageViaIdSubtitles->getIdLanguage() : null;
    }

    public function setIdSubtitles(?language $language): static
    {
        $this->languageViaIdSubtitles = $language;

        return $this;
    }

}