<?php

namespace App\Entity;

use App\Repository\WorkerTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkerTypeRepository::class)]
#[ORM\Table(name: "worker_type")]
#[ORM\UniqueConstraint(name: "uq_type_name", columns: ["type_name"])]
class WorkerType
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_worker_type;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    private $type_name;

    #[ORM\OneToMany(targetEntity: Worker::class, mappedBy: "workerType")]
    private $workers;

    #[ORM\OneToMany(targetEntity: WorkerTypePermissions::class, mappedBy: "workerType")]
    private $workerTypePermissions;

    public function __construct()
    {
        $this->workers = new ArrayCollection();
        $this->workerTypePermissions = new ArrayCollection();
    }

    public function getIdWorkerType(): ?int
    {
        return $this->id_worker_type;
    }

    public function getTypeName(): ?string
    {
        return $this->type_name;
    }

    public function setTypeName(string $type_name): static
    {
        $this->type_name = $type_name;

        return $this;
    }

    /**
     * @return Collection<int, Worker>
     */
    public function getWorkers(): Collection
    {
        return $this->workers;
    }

    public function addWorker(Worker $worker): static
    {
        if (!$this->workers->contains($worker)) {
            $this->workers->add($worker);
            $worker->setWorkerType($this);
        }

        return $this;
    }

    public function removeWorker(Worker $worker): static
    {
        if ($this->workers->removeElement($worker)) {
            // set the owning side to null (unless already changed)
            if ($worker->getWorkerType() === $this) {
                $worker->setWorkerType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WorkerTypePermissions>
     */
    public function getWorkerTypePermissions(): Collection
    {
        return $this->workerTypePermissions;
    }

    public function addWorkerTypePermission(WorkerTypePermissions $workerTypePermission): static
    {
        if (!$this->workerTypePermissions->contains($workerTypePermission)) {
            $this->workerTypePermissions->add($workerTypePermission);
            $workerTypePermission->setWorkerType($this);
        }

        return $this;
    }

    public function removeWorkerTypePermission(WorkerTypePermissions $workerTypePermission): static
    {
        if ($this->workerTypePermissions->removeElement($workerTypePermission)) {
            // set the owning side to null (unless already changed)
            if ($workerTypePermission->getWorkerType() === $this) {
                $workerTypePermission->setWorkerType(null);
            }
        }

        return $this;
    }
}
