<?php

namespace App\Entity;

use App\Repository\RoutesRepository;
use App\Repository\StopTimesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StopTimesRepository::class)]
class StopTimes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private string $tripId;
    #[ORM\Column(length: 255)]
    private ?\DateTime $arrivalTime = null;
    #[ORM\Column(length: 255)]
    private ?\DateTime $departureTime = null;
    #[ORM\Column(length: 255)]
    private int $stopId;
    #[ORM\Column(length: 255)]
    private int $stopSequence;
    #[ORM\Column(length: 255)]
    private string $stopHeadsign;
    #[ORM\Column(length: 255)]
    private float $shapeDistTraveled;

    public function getDepartureTime(): ?\DateTime
    {
        return $this->departureTime;
    }

    public function setDepartureTime(?\DateTime $departureTime): void
    {
        $this->departureTime = $departureTime;
    }

    public function getStopId(): ?int
    {
        return $this->stopId;
    }

    public function setStopId(?int $stopId): void
    {
        $this->stopId = $stopId;
    }

    public function getStopSequence(): ?int
    {
        return $this->stopSequence;
    }

    public function setStopSequence(?int $stopSequence): void
    {
        $this->stopSequence = $stopSequence;
    }

    public function getStopHeadsign(): ?string
    {
        return $this->stopHeadsign;
    }

    public function setStopHeadsign(?string $stopHeadsign): void
    {
        $this->stopHeadsign = $stopHeadsign;
    }

    public function getShapeDistTraveled(): ?float
    {
        return $this->shapeDistTraveled;
    }

    public function setShapeDistTraveled(?float $shapeDistTraveled): void
    {
        $this->shapeDistTraveled = $shapeDistTraveled;
    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getArrivalTime(): ?\DateTime
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(\DateTime $arrivalTime): void
    {
        $this->arrivalTime = $arrivalTime;
    }
}
