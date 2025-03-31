<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: "client")]
#[ORM\UniqueConstraint(name: "client_nick_key", columns: ["nick"])]
#[ORM\UniqueConstraint(name: "client_mail_key", columns: ["mail"])]
class client
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id_client;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    private $client_name;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    private $client_surname;

    #[ORM\Column(type: "string", length: 40, nullable: false)]
    private $nick;

    #[ORM\Column(type: "string", length: 80, nullable: false)]
    private $password_hash;

    #[ORM\Column(type: "string", length: 320, nullable: false)]
    private $mail;

    #[ORM\OneToMany(targetEntity: \reservation::class, mappedBy: "client")]
    private $reservations;

    #[ORM\OneToMany(targetEntity: \client_sessions::class, mappedBy: "client")]
    private $clientSessions;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->clientSessions = new ArrayCollection();
    }

    public function getIdClient(): ?int
    {
        return $this->id_client;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): static
    {
        $this->client_name = $client_name;

        return $this;
    }

    public function getClientSurname(): ?string
    {
        return $this->client_surname;
    }

    public function setClientSurname(string $client_surname): static
    {
        $this->client_surname = $client_surname;

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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setClient($this);
        }

        return $this;
    }

    public function removeReservation(reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getClient() === $this) {
                $reservation->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, client_sessions>
     */
    public function getClientSessions(): Collection
    {
        return $this->clientSessions;
    }

    public function addClientSession(client_sessions $clientSession): static
    {
        if (!$this->clientSessions->contains($clientSession)) {
            $this->clientSessions->add($clientSession);
            $clientSession->setClient($this);
        }

        return $this;
    }

    public function removeClientSession(client_sessions $clientSession): static
    {
        if ($this->clientSessions->removeElement($clientSession)) {
            // set the owning side to null (unless already changed)
            if ($clientSession->getClient() === $this) {
                $clientSession->setClient(null);
            }
        }

        return $this;
    }
}