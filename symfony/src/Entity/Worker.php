<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\WorkerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

//#[ApiResource(
//    normalizationContext: ['groups' => ['Worker:read']],
//    denormalizationContext: ['groups' => ['Worker:write']]
//)]
#[ORM\Entity(repositoryClass: WorkerRepository::class)]
#[ORM\Table(name: "worker")]
#[ORM\Index(name: "worker_id_worker_type_idx", columns: ["id_worker_type"])]
#[ORM\UniqueConstraint(name: "worker_nick_key", columns: ["nick"])]
class Worker
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id_worker;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    private string $worker_name;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    private string $worker_surname;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    private string $nick;

    #[ORM\Column(type: "string", length: 80, nullable: false)]
    private string $password_hash;

    #[ORM\OneToMany(targetEntity: WorkerSessions::class, mappedBy: "worker")]
    private iterable $workerSessions;

    #[ORM\ManyToOne(targetEntity: WorkerType::class, inversedBy: "workers")]
    #[ORM\JoinColumn(name: "id_worker_type",
            referencedColumnName: "id_worker_type",
            nullable: false,
            onDelete: "RESTRICT")]
    private WorkerType $workerType;

    public function __construct()
    {
        $this->workerSessions = new ArrayCollection();
    }

    public function getIdWorker(): ?int
    {
        return $this->id_worker;
    }

    public function getWorkerName(): ?string
    {
        return $this->worker_name;
    }

    public function setWorkerName(string $worker_name): static
    {
        $this->worker_name = $worker_name;

        return $this;
    }

    public function getWorkerSurname(): ?string
    {
        return $this->worker_surname;
    }

    public function setWorkerSurname(string $worker_surname): static
    {
        $this->worker_surname = $worker_surname;

        return $this;
    }

    public function getNick(): ?string
    {
        return $this->nick;
    }

    public function setNick(string $nick): static
    {
        $this->nick = $nick;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): static
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    /**
     * @return Collection<int, WorkerSessions>
     */
    public function getWorkerSessions(): Collection
    {
        return $this->workerSessions;
    }

    public function addWorkerSession(WorkerSessions $workerSession): static
    {
        if (!$this->workerSessions->contains($workerSession)) {
            $this->workerSessions->add($workerSession);
            $workerSession->setWorker($this);
        }

        return $this;
    }

    public function removeWorkerSession(WorkerSessions $workerSession): static
    {
        if ($this->workerSessions->removeElement($workerSession)) {
            // set the owning side to null (unless already changed)
            if ($workerSession->getWorker() === $this) {
                $workerSession->setWorker(null);
            }
        }

        return $this;
    }

    public function getWorkerType(): ?WorkerType
    {
        return $this->workerType;
    }

    public function setWorkerType(?WorkerType $workerType): static
    {
        $this->workerType = $workerType;

        return $this;
    }
}