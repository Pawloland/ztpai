<?php

namespace App\Entity;

use App\Repository\WorkerTypePermissionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkerTypePermissionsRepository::class)]
#[ORM\Table(name: "worker_type_permissions")]
#[ORM\Index(name: "idx_worker_type_permissions_worker_type", columns: ["id_worker_type"])]
#[ORM\Index(name: "idx_worker_type_permissions_perm", columns: ["id_perm"])]
class worker_type_permissions
{
        #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: \worker_type::class, inversedBy: "workerTypePermissions")]
        #[ORM\JoinColumn(
                name: "id_worker_type",
                referencedColumnName: "id_worker_type",
                nullable: false,
                onDelete: "RESTRICT"
        )]
        private $workerType;

        #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: \permissions::class, inversedBy: "workerTypePermissions")]
        #[ORM\JoinColumn(
                name: "id_perm",
                referencedColumnName: "id_perm",
                nullable: false,
                onDelete: "RESTRICT"
        )]
        private $permissions;

        public function getWorkerType(): ?worker_type
        {
            return $this->workerType;
        }

        public function setWorkerType(?worker_type $workerType): static
        {
            $this->workerType = $workerType;

            return $this;
        }

        public function getPermissions(): ?permissions
        {
            return $this->permissions;
        }

        public function setPermissions(?permissions $permissions): static
        {
            $this->permissions = $permissions;

            return $this;
        }
}
