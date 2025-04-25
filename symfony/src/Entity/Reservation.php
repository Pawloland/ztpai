<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReservationRepository;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: "reservation")]
#[ORM\Index(name: "reservation_screening_idx", columns: ["id_screening"])]
#[ORM\Index(name: "reservation_discount_idx", columns: ["id_discount"])]
#[ORM\Index(name: "reservation_client_idx", columns: ["id_client"])]
#[ORM\UniqueConstraint(name: "reservation_seat_screening_idx", columns: ["id_seat","id_screening"])]
class Reservation
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id_reservation;

    #[ORM\Column(type: "decimal", nullable: false,scale: 2)]
    private string $total_price_netto;

    #[ORM\Column(type: "decimal", nullable: false, scale: 2)]
    private string $total_price_brutto;

    #[ORM\Column(type: "decimal", nullable: false, precision:4, scale: 2)]
    private string $vat_percentage;

    #[ORM\Column(type: "datetimetz", nullable: false, options: ["default"=>"CURRENT_TIMESTAMP"])]
    private $reservation_date;

    #[ORM\Column(type: "string", length: 10, nullable: true)]
    private string $nip;

    #[ORM\Column(type: "string", length: 26, nullable: true)]
    private string $nrb;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private string $address_street;

    #[ORM\Column(type: "string", length: 10, nullable: true)]
    private string $address_nr;

    #[ORM\Column(type: "string", length: 10, nullable: true)]
    private string $address_flat;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private string $address_city;

    #[ORM\Column(type: "string", length: 11, nullable: true)]
    private string $address_zip;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: "reservations")]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_client", onDelete: "RESTRICT")]
    private Client $client;

    #[ORM\ManyToOne(targetEntity: Discount::class, inversedBy: "reservations")]
    #[ORM\JoinColumn(name: "id_discount",
            referencedColumnName: "id_discount",
            onDelete: "RESTRICT")]
    private Discount $discount;

    #[ORM\ManyToOne(targetEntity: Screening::class, inversedBy: "reservation")]
    #[ORM\JoinColumn(name: "id_screening",
            referencedColumnName: "id_screening",
            nullable: false,
            onDelete: "RESTRICT")]
    private Screening $screening;

    #[ORM\ManyToOne(targetEntity: Seat::class, inversedBy: "reservation")]
    #[ORM\JoinColumn(name: "id_seat",
            referencedColumnName: "id_seat",
            nullable: false,
            onDelete: "RESTRICT")]
    private Seat $seat;

    public function getIdReservation(): ?int
    {
        return $this->id_reservation;
    }

    public function getTotalPriceNetto(): ?string
    {
        return $this->total_price_netto;
    }

    public function setTotalPriceNetto(string $total_price_netto): static
    {
        $this->total_price_netto = $total_price_netto;

        return $this;
    }

    public function getTotalPriceBrutto(): ?string
    {
        return $this->total_price_brutto;
    }

    public function setTotalPriceBrutto(string $total_price_brutto): static
    {
        $this->total_price_brutto = $total_price_brutto;

        return $this;
    }

    public function getVatPercentage(): ?string
    {
        return $this->vat_percentage;
    }

    public function setVatPercentage(string $vat_percentage): static
    {
        $this->vat_percentage = $vat_percentage;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservation_date;
    }

    public function setReservationDate(\DateTimeInterface $reservation_date): static
    {
        $this->reservation_date = $reservation_date;

        return $this;
    }

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public function setNip(?string $nip): static
    {
        $this->nip = $nip;

        return $this;
    }

    public function getNrb(): ?string
    {
        return $this->nrb;
    }

    public function setNrb(?string $nrb): static
    {
        $this->nrb = $nrb;

        return $this;
    }

    public function getAddressStreet(): ?string
    {
        return $this->address_street;
    }

    public function setAddressStreet(?string $address_street): static
    {
        $this->address_street = $address_street;

        return $this;
    }

    public function getAddressNr(): ?string
    {
        return $this->address_nr;
    }

    public function setAddressNr(?string $address_nr): static
    {
        $this->address_nr = $address_nr;

        return $this;
    }

    public function getAddressFlat(): ?string
    {
        return $this->address_flat;
    }

    public function setAddressFlat(?string $address_flat): static
    {
        $this->address_flat = $address_flat;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->address_city;
    }

    public function setAddressCity(?string $address_city): static
    {
        $this->address_city = $address_city;

        return $this;
    }

    public function getAddressZip(): ?string
    {
        return $this->address_zip;
    }

    public function setAddressZip(?string $address_zip): static
    {
        $this->address_zip = $address_zip;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getScreening(): ?Screening
    {
        return $this->screening;
    }

    public function setScreening(?Screening $screening): static
    {
        $this->screening = $screening;

        return $this;
    }

    public function getSeat(): ?Seat
    {
        return $this->seat;
    }

    public function setSeat(?Seat $seat): static
    {
        $this->seat = $seat;

        return $this;
    }
}