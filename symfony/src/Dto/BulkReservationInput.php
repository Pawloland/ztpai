<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class BulkReservationInput
{
    #[Assert\NotBlank]
    #[Assert\Type("integer")]
    #[Groups(['BulkReservation:write'])]
    public int $id_screening;

    #[Assert\Type("string")]
    #[Groups(['BulkReservation:write'])]
    public ?string $discount_name = null;


    /**
     * @var int[] Array of ids of selected seats
     */
    #[Assert\NotBlank]
    #[Assert\Type("array")]
    #[Assert\Count(min: 1, minMessage: "At least one seat must be selected")]
    #[Assert\All([
        new Assert\Type("integer"),
        new Assert\NotBlank()
    ])]
    #[Groups(['BulkReservation:write'])]
    public array $id_seat;
}
