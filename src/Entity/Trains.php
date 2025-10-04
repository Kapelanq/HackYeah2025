<?php

namespace App\Entity;

use App\Repository\TrainsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainsRepository::class)]
class Trains
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $positionLat = null;

    #[ORM\Column]
    private ?float $positionLon = null;

    #[ORM\Column]
    private ?int $lastStop = null;

    #[ORM\Column]
    private ?string $tripId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPositionLat(): ?float
    {
        return $this->positionLat;
    }

    public function setPositionLat(float $position_lat): static
    {
        $this->positionLat = $position_lat;

        return $this;
    }

    public function getPositionLon(): ?float
    {
        return $this->positionLon;
    }

    public function setPositionLon(float $positionLon): static
    {
        $this->positionLon = $positionLon;

        return $this;
    }

    public function getLastStop(): ?int
    {
        return $this->lastStop;
    }

    public function setLastStop(int $lastStop): static
    {
        $this->lastStop = $lastStop;

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
            'name' => $this->getName(),
            'positionLat' => $this->getPositionLat(),
            'positionLon' => $this->getPositionLon(),
            'lastStop' => $this->getLastStop(),
            'tripId' => $this->getTripId()
        ];
    }
}
