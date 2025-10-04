<?php

namespace App\Entity;

use App\Repository\ReportsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportsRepository::class)]
class Reports
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tripId = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(length: 255)]
    private ?float $reportLat = null;

    #[ORM\Column]
    private ?float $reportLon = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\Column]
    private ?\string $description = null;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getReportLat(): ?float
    {
        return $this->reportLat;
    }

    public function setReportLat(float $reportLat): static
    {
        $this->reportLat = $reportLat;

        return $this;
    }

    public function getReportLon(): ?float
    {
        return $this->reportLon;
    }

    public function setReportLon(float $reportLon): static
    {
        $this->reportLon = $reportLon;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }
}
