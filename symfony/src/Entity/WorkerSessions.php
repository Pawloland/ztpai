<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\WorkerSessionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    normalizationContext: ['groups' => ['WorkerSessions:read']],
    denormalizationContext: ['groups' => ['WorkerSessions:write']]
)]
#[ORM\Entity(repositoryClass: WorkerSessionsRepository::class)]
#[ORM\Table(name: "worker_sessions")]
#[ORM\UniqueConstraint(name: "worker_sessions_session_token_key", columns: ["session_token"])]
class WorkerSessions
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id_session_worker;

    #[ORM\Column(type: "string", length: 80, nullable: false)]
    private string $session_token;

    #[ORM\Column(type: "datetimetz", nullable: false)]
    private $expiration_date;

    #[ORM\ManyToOne(targetEntity: Worker::class, inversedBy: "workerSessions")]
    #[ORM\JoinColumn(name: "id_worker",
            referencedColumnName: "id_worker",
            nullable: false,
            onDelete: "CASCADE")]
    private Worker $worker;

    public function getIdSessionWorker(): ?int
    {
        return $this->id_session_worker;
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

    public function getWorker(): ?Worker
    {
        return $this->worker;
    }

    public function setWorker(?Worker $worker): static
    {
        $this->worker = $worker;

        return $this;
    }
}