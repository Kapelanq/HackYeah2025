<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReportsCountRepository;

#[ORM\Entity(repositoryClass: ReportsCountRepository::class)]
#[ORM\Table(name: "reports_count")]
class ReportsCount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Reports::class)]
    #[ORM\JoinColumn(name: "report_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Reports $report;

    #[ORM\Column(type: "boolean")]
    private bool $isGood;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReport(): Reports
    {
        return $this->report;
    }

    public function setReport(Reports $report): static
    {
        $this->report = $report;
        return $this;
    }

    public function getIsGood(): bool
    {
        return $this->isGood;
    }

    public function setIsGood(bool $isGood): static
    {
        $this->isGood = $isGood;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'reportId' => $this->getReport()->getId(),
            'isGood' => $this->getIsGood(),
        ];
    }
}
