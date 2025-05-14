<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Dto\BulkReservationInput;
use App\Repository\BulkReservationRepository;
use App\State\BulkReservationStateProcessorPOST;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [new Post(
        security: "is_granted('CLIENT')",
        input: BulkReservationInput::class,
        deserialize: true,
        processor: BulkReservationStateProcessorPOST::class,

    ),],
    normalizationContext: ['groups' => ['BulkReservation:read']],
    denormalizationContext: ['groups' => ['BulkReservation:write']]
)]
#[ORM\Entity(repositoryClass: BulkReservationRepository::class)]
#[ORM\Table(name: "bulk_reservation")]
class BulkReservation
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[Groups(['BulkReservation:read'])]
    private int $id_bulk_reservation;

    #[ORM\Column(type: "boolean", nullable: false, insertable: false, options: ["default" => false])]
    private bool $closed;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: "bulkReservation")]
    #[Groups(['BulkReservation:read'])]
    private iterable $reservation;


    public function getIdBulkReservation(): int
    {
        return $this->id_bulk_reservation;
    }

    public function setIdBulkReservation(int $id_bulk_reservation): self
    {
        $this->id_bulk_reservation = $id_bulk_reservation;

        return $this;
    }

    public function getClosed(): string
    {
        return $this->closed;
    }

    public function setClosed(string $closed): self
    {
        $this->closed = $closed;

        return $this;
    }

    public function getReservation(): iterable
    {
        return $this->reservation;
    }



//    public function setReservation(iterable $reservation): self
//    {
//        $this->reservation = $reservation;
//
//        return $this;
//    }


}