<?php

namespace App\Entity;

use App\Repository\ReportsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportsRepository::class)]
class Reports
{
    public const TYPE_TRAIN_DELAY = 'trainDelay';
    public const TYPE_TRAIN_FAILURE = 'trainFailure';
    public const TYPE_ROAD_FAILURE = 'roadFailure';
    public const TYPE_PASS_OTHER_TRAIN = 'passOtherTrain';
    public const TYPE_COLLISION = 'collision';
    public const TYPE_DERAILMENT = 'derailment';
    public const TYPE_OTHER = 'other';
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
    private ?string $description = null;
    #[ORM\Column]
    private ?int $delayMinutes = null;
    #[ORM\Column]
    private ?bool $completed = null;

    #[ORM\Column]
    private ?\DateTime $date = null;


    #[ORM\OneToMany(targetEntity: ReportsCount::class, mappedBy: 'report', cascade: ['persist', 'remove'])]
    private Collection $reportsCount;

    public function __construct()
    {
        $this->reportsCount = new ArrayCollection();
    }

    public function getReportsCounts(): Collection
    {
        return $this->reportsCount;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDelayMinutes(): ?int
    {
        return $this->delayMinutes;
    }

    public function setDelayMinutes(int $delayMinutes): static
    {
        $this->delayMinutes = $delayMinutes;
        return $this;
    }

    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'tripId' => $this->getTripId(),
            'type' => $this->getType(),
            'userId' => $this->getUserId(),
            'reportLat' => $this->getReportLat(),
            'reportLon' => $this->getReportLon(),
            'description' => $this->getDescription(),
            'delayMinutes' => $this->getDelayMinutes(),
            'completed' => $this->getCompleted()
        ];
    }
}
