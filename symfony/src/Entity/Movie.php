<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\Repository\MovieRepository;
use App\State\MovieStateProcessorPOST;
use ArrayObject;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            uriTemplate: '/movies',
            inputFormats: ['multipart' => ['multipart/form-data']],
            openapi: new Operation(
                summary: 'Create a new movie with a poster',
                requestBody: new RequestBody(
                    description: 'Movie fields plus a poster file',
                    content: new ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => ['type' => 'string'],
                                    'original_title' => ['type' => 'string'],
                                    'duration' => ['type' => 'string', 'format' => 'time'],
                                    'description' => ['type' => 'string'],
                                    'id_language' => ['type' => 'integer'],
                                    'id_dubbing' => ['type' => 'integer'],
                                    'id_subtitles' => ['type' => 'integer'],
                                    'poster' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ]
                                ],
                                'required' => ['title', 'original_title', 'duration', 'id_language']
                            ]
                        ]
                    ]),
                    required: true,
                )
            ),
            normalizationContext: ['groups' => ['Movie:read']],
            deserialize: false,
            name: 'post_movie_with_poster',
            processor: MovieStateProcessorPOST::class,
        ),
        new Get(),
        new Delete(
            security: "is_granted('WORKER', object)",
        ),
        new Patch(),
    ],
    normalizationContext: ['groups' => ['Movie:read']],
    denormalizationContext: ['groups' => ['Movie:write']]
)]
#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\Table(name: "movie")]
#[ORM\Index(name: "fk_movie_language", columns: ["id_language"])]
#[ORM\Index(name: "fk_movie_dubbing", columns: ["id_dubbing"])]
#[ORM\Index(name: "fk_movie_subtitles", columns: ["id_subtitles"])]
#[ApiFilter(
    OrderFilter::class,
    properties: ['title'],
)]
class Movie
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups(['Movie:read','Screening:read'])]
    private int $id_movie;

    #[ORM\Column(type: "string", length: 80, nullable: false)]
    #[Groups(['Movie:read', 'Movie:write','Screening:read'])]
    private string $title;

    #[ORM\Column(type: "string", length: 80, nullable: false)]
    #[Groups(['Movie:read', 'Movie:write'])]
    private string $original_title;

    #[ORM\Column(type: "time", nullable: false)]
    #[Groups(['Movie:read', 'Movie:write'])]
    private DateTimeInterface $duration;

    #[ORM\Column(type: "string", length: 500, nullable: true)]
    #[Groups(['Movie:read', 'Movie:write'])]
    private ?string $description;

    #[ORM\Column(type: "guid", nullable: true,
        insertable: false, # since this property is read-only in api-platform (set by Groups attribute), this option (insertable: false) prevents Doctrine from trying to insert a NULL
        # which api-platform passes to Doctrine, thus it forces DB to generate UUID when inserting new Movie, but it doesn't prevent changing the value to NULL, or any other UUID with PATCH request
        options: ['default' => 'gen_random_uuid()'], # this is to appease Doctrine, so it doesn't try to drop the default value in migration (set in sql dump and in column definition bellow)
        columnDefinition: "uuid DEFAULT gen_random_uuid()" # this manually sets actual column definition, so it is the same as in sql dump, allowing for default value to be set by the database
    )]
    #[Groups(['Movie:read'])]
    private ?string $poster;

    #[ORM\OneToOne(targetEntity: MovieGenre::class, mappedBy: "movie")]
    #[Groups(['Movie:read'])]
    private MovieGenre $movieGenres;

    #[ORM\OneToMany(targetEntity: Screening::class, mappedBy: "movie")]
    #[Groups(['Movie:read'])]
    private iterable $screenings;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: "movieViaIdLanguage")]
    #[ORM\JoinColumn(name: "id_language",
        referencedColumnName: "id_language",
        nullable: false,
        onDelete: "RESTRICT")]
    #[Groups(['Movie:read', 'Movie:write'])]
    private Language $languageViaIdLanguage;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: "movieViaIdDubbing")]
    #[ORM\JoinColumn(name: "id_dubbing",
        referencedColumnName: "id_language",
        onDelete: "RESTRICT")]
    #[Groups(['Movie:read', 'Movie:write'])]
    private ?Language $languageViaIdDubbing;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: "movieViaIdSubtitles")]
    #[ORM\JoinColumn(name: "id_subtitles",
        referencedColumnName: "id_language",
        onDelete: "RESTRICT")]
    #[Groups(['Movie:read', 'Movie:write'])]
    private ?Language $languageViaIdSubtitles;

    public function __construct()
    {
        $this->screenings = new ArrayCollection();
    }

    public function getIdMovie(): int
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

    public function getMovieGenres(): ?MovieGenre
    {
        return $this->movieGenres;
    }

    public function setMovieGenres(?MovieGenre $movieGenres): static
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
     * @return Collection<int, Screening>
     */
    public function getScreenings(): Collection
    {
        return $this->screenings;
    }

    public function addScreening(Screening $screening): static
    {
        if (!$this->screenings->contains($screening)) {
            $this->screenings->add($screening);
            $screening->setMovie($this);
        }

        return $this;
    }

    public function removeScreening(Screening $screening): static
    {
        if ($this->screenings->removeElement($screening)) {
            // set the owning side to null (unless already changed)
            if ($screening->getMovie() === $this) {
                $screening->setMovie(null);
            }
        }

        return $this;
    }

    public function getLanguageViaIdLanguage(): ?Language
    {
        return $this->languageViaIdLanguage;
    }

    public function setLanguageViaIdLanguage(?Language $languageViaIdLanguage): static
    {
        $this->languageViaIdLanguage = $languageViaIdLanguage;

        return $this;
    }

    public function getLanguageViaIdDubbing(): ?Language
    {
        return $this->languageViaIdDubbing;
    }

    public function setLanguageViaIdDubbing(?Language $languageViaIdDubbing): static
    {
        $this->languageViaIdDubbing = $languageViaIdDubbing;

        return $this;
    }

    public function getLanguageViaIdSubtitles(): ?Language
    {
        return $this->languageViaIdSubtitles;
    }

    public function setLanguageViaIdSubtitles(?Language $languageViaIdSubtitles): static
    {
        $this->languageViaIdSubtitles = $languageViaIdSubtitles;

        return $this;
    }

    public function getIdLanguage(): int
    {
        return $this->languageViaIdLanguage->getIdLanguage();
    }

    public function setIdLanguage(?Language $language): static
    {
        $this->languageViaIdLanguage = $language;

        return $this;
    }

    public function getIdDubbing(): ?int
    {
        return $this->languageViaIdDubbing?->getIdLanguage();
    }

    public function setIdDubbing(?Language $language): static
    {
        $this->languageViaIdDubbing = $language;

        return $this;
    }


    public function getIdSubtitles(): ?int
    {
        return $this->languageViaIdSubtitles?->getIdLanguage();
    }

    public function setIdSubtitles(?Language $language): static
    {
        $this->languageViaIdSubtitles = $language;

        return $this;
    }

}