<?php

namespace App\Entity;

use App\Repository\ClientSessionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientSessionsRepository::class)]
#[ORM\Table(name: "client_sessions")]
#[ORM\UniqueConstraint(name: "client_sessions_session_token_key", columns: ["session_token"])]
class client_sessions
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_session_client;

    #[ORM\Column(type: "string", length: 80, nullable: false)]
    private $session_token;

    #[ORM\Column(type: "datetimetz", nullable: false)]
    private $expiration_date;

    #[ORM\ManyToOne(targetEntity: \client::class, inversedBy: "clientSessions")]
    #[ORM\JoinColumn(name: "id_client",
            referencedColumnName: "id_client",
            nullable: false,
            onDelete: "CASCADE")]
    private $client;

    public function getIdSessionClient(): ?int
    {
        return $this->id_session_client;
    }

    public function getSessionToken(): ?string
    {
        return $this->session_token;
    }

    public function setSessionToken(string $session_token): static
    {
        $this->session_token = $session_token;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(\DateTimeInterface $expiration_date): static
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getClient(): ?client
    {
        return $this->client;
    }

    public function setClient(?client $client): static
    {
        $this->client = $client;

        return $this;
    }
}