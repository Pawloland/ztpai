<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LanguageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['Language:read']],
    denormalizationContext: ['groups' => ['Language:write']]
)]
#[ORM\Entity(repositoryClass: LanguageRepository::class)]
#[ORM\Table(name: "language")]
#[ORM\UniqueConstraint(name: "language_language_name_key", columns: ["language_name"])]
#[ORM\UniqueConstraint(name: "language_code_key", columns: ["code"])]
class Language
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups(['Language:read'])]
    private int $id_language;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    #[Groups(['Language:read'])]
    private string $language_name;

    #[ORM\Column(type: "string", length: 5, nullable: false)]
    #[Groups(['Language:read'])]
    private string $code;

    #[ORM\OneToMany(targetEntity: Movie::class, mappedBy: "languageViaIdLanguage")]
    private iterable $movieViaIdLanguage;

    #[ORM\OneToMany(targetEntity: Movie::class, mappedBy: "languageViaIdDubbing")]
    private iterable $movieViaIdDubbing;

    #[ORM\OneToMany(targetEntity: Movie::class, mappedBy: "languageViaIdSubtitles")]
    private iterable $movieViaIdSubtitles;

    public function __construct()
    {
        $this->movieViaIdLanguage = new ArrayCollection();
        $this->movieViaIdDubbing = new ArrayCollection();
        $this->movieViaIdSubtitles = new ArrayCollection();
    }

    public function getIdLanguage(): int
    {
        return $this->id_language;
    }

    public function getLanguageName(): ?string
    {
        return $this->language_name;
    }

    public function setLanguageName(string $language_name): static
    {
        $this->language_name = $language_name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovieViaIdLanguage(): Collection
    {
        return $this->movieViaIdLanguage;
    }

    public function addMovieViaIdLanguage(Movie $movieViaIdLanguage): static
    {
        if (!$this->movieViaIdLanguage->contains($movieViaIdLanguage)) {
            $this->movieViaIdLanguage->add($movieViaIdLanguage);
            $movieViaIdLanguage->setLanguageViaIdLanguage($this);
        }

        return $this;
    }

    public function removeMovieViaIdLanguage(Movie $movieViaIdLanguage): static
    {
        if ($this->movieViaIdLanguage->removeElement($movieViaIdLanguage)) {
            // set the owning side to null (unless already changed)
            if ($movieViaIdLanguage->getLanguageViaIdLanguage() === $this) {
                $movieViaIdLanguage->setLanguageViaIdLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovieViaIdDubbing(): Collection
    {
        return $this->movieViaIdDubbing;
    }

    public function addMovieViaIdDubbing(Movie $movieViaIdDubbing): static
    {
        if (!$this->movieViaIdDubbing->contains($movieViaIdDubbing)) {
            $this->movieViaIdDubbing->add($movieViaIdDubbing);
            $movieViaIdDubbing->setLanguageViaIdDubbing($this);
        }

        return $this;
    }

    public function removeMovieViaIdDubbing(Movie $movieViaIdDubbing): static
    {
        if ($this->movieViaIdDubbing->removeElement($movieViaIdDubbing)) {
            // set the owning side to null (unless already changed)
            if ($movieViaIdDubbing->getLanguageViaIdDubbing() === $this) {
                $movieViaIdDubbing->setLanguageViaIdDubbing(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovieViaIdSubtitles(): Collection
    {
        return $this->movieViaIdSubtitles;
    }

    public function addMovieViaIdSubtitle(Movie $movieViaIdSubtitle): static
    {
        if (!$this->movieViaIdSubtitles->contains($movieViaIdSubtitle)) {
            $this->movieViaIdSubtitles->add($movieViaIdSubtitle);
            $movieViaIdSubtitle->setLanguageViaIdSubtitles($this);
        }

        return $this;
    }

    public function removeMovieViaIdSubtitle(Movie $movieViaIdSubtitle): static
    {
        if ($this->movieViaIdSubtitles->removeElement($movieViaIdSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($movieViaIdSubtitle->getLanguageViaIdSubtitles() === $this) {
                $movieViaIdSubtitle->setLanguageViaIdSubtitles(null);
            }
        }

        return $this;
    }
}