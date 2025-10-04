<?php

namespace App\Entity;

use App\Repository\TicketsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketsRepository::class)]
class Tickets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ticketId = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column]
    private ?bool $hasExpired = null;

    #[ORM\Column(length: 255)]
    private ?string $tripId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicketId(): ?string
    {
        return $this->ticketId;
    }

    public function setTicketId(string $ticketId): static
    {
        $this->ticketId = $ticketId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function hasExpired(): ?bool
    {
        return $this->hasExpired;
    }

    public function setHasExpired(bool $hasExpired): static
    {
        $this->hasExpired = $hasExpired;

        return $this;
    }

    public function getTripId(): ?string
    {
        return $this->tripId;
    }

    public function setTripId(string $tripId): static
    {
        $this->tripId = $tripId;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'ticketId' => $this->getTicketId(),
            'userId' => $this->getUserId(),
            'hasExpired' => $this->hasExpired,
            'tripId' => $this->getTripId()
        ];
    }
}
