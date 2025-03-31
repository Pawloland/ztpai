<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
#[ORM\Table(name: "permissions")]
#[ORM\UniqueConstraint(name: "permissions_perm_name_key", columns: ["perm_name"])]
class permissions
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_perm;

    #[ORM\Column(type: "string", length: 60, nullable: false)]
    private $perm_name;

    #[ORM\OneToMany(targetEntity: \worker_type_permissions::class, mappedBy: "permissions")]
    private $workerTypePermissions;

    public function __construct()
    {
        $this->workerTypePermissions = new ArrayCollection();
    }

    public function getIdPerm(): ?int
    {
        return $this->id_perm;
    }

    public function getPermName(): ?string
    {
        return $this->perm_name;
    }

    public function setPermName(string $perm_name): static
    {
        $this->perm_name = $perm_name;

        return $this;
    }

    /**
     * @return Collection<int, worker_type_permissions>
     */
    public function getWorkerTypePermissions(): Collection
    {
        return $this->workerTypePermissions;
    }

    public function addWorkerTypePermission(worker_type_permissions $workerTypePermission): static
    {
        if (!$this->workerTypePermissions->contains($workerTypePermission)) {
            $this->workerTypePermissions->add($workerTypePermission);
            $workerTypePermission->setPermissions($this);
        }

        return $this;
    }

    public function removeWorkerTypePermission(worker_type_permissions $workerTypePermission): static
    {
        if ($this->workerTypePermissions->removeElement($workerTypePermission)) {
            // set the owning side to null (unless already changed)
            if ($workerTypePermission->getPermissions() === $this) {
                $workerTypePermission->setPermissions(null);
            }
        }

        return $this;
    }
}
