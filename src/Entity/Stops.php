<?php

namespace App\Entity;

use App\Repository\StopsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StopsRepository::class)]
class Stops
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $stopId = null;

    #[ORM\Column(length: 255)]
    private ?string $stopName = null;

    #[ORM\Column]
    private ?float $stopLat = null;

    #[ORM\Column]
    private ?float $stopLon = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStopId(): ?int
    {
        return $this->stopId;
    }

    public function setStopId(int $stopId): static
    {
        $this->stopId = $stopId;

        return $this;
    }

    public function getStopName(): ?string
    {
        return $this->stopName;
    }

    public function setStopName(string $stopName): static
    {
        $this->stopName = $stopName;

        return $this;
    }

    public function getStopLat(): ?float
    {
        return $this->stopLat;
    }

    public function setStopLat(float $stopLat): static
    {
        $this->stopLat = $stopLat;

        return $this;
    }

    public function getStopLon(): ?float
    {
        return $this->stopLon;
    }

    public function setStopLon(float $stopLon): static
    {
        $this->stopLon = $stopLon;

        return $this;
    }
}
