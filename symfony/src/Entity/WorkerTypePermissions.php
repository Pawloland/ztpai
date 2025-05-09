<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\WorkerTypePermissionsRepository;
use Doctrine\ORM\Mapping as ORM;

//#[ApiResource(
//    normalizationContext: ['groups' => ['WorkerTypePermissions:read']],
//    denormalizationContext: ['groups' => ['WorkerTypePermissions:write']],
//)]
#[ORM\Entity(repositoryClass: WorkerTypePermissionsRepository::class)]
#[ORM\Table(name: "worker_type_permissions")]
#[ORM\Index(name: "idx_worker_type_permissions_worker_type", columns: ["id_worker_type"])]
#[ORM\Index(name: "idx_worker_type_permissions_perm", columns: ["id_perm"])]
class WorkerTypePermissions
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: WorkerType::class, inversedBy: "workerTypePermissions")]
    #[ORM\JoinColumn(
        name: "id_worker_type",
        referencedColumnName: "id_worker_type",
        nullable: false,
        onDelete: "RESTRICT"
    )]
    #[ApiProperty(identifier: true)]
    private WorkerType $workerType;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Permissions::class, inversedBy: "workerTypePermissions")]
    #[ORM\JoinColumn(
        name: "id_perm",
        referencedColumnName: "id_perm",
        nullable: false,
        onDelete: "RESTRICT"
    )]
    #[ApiProperty(identifier: true)]
    private Permissions $permissions;

    public function getWorkerType(): ?WorkerType
    {
        return $this->workerType;
    }

    public function setWorkerType(?WorkerType $workerType): static
    {
        $this->workerType = $workerType;

        return $this;
    }

    public function getPermissions(): ?Permissions
    {
        return $this->permissions;
    }

    public function setPermissions(?Permissions $permissions): static
    {
        $this->permissions = $permissions;

        return $this;
    }
}
