<?php

namespace App\Repository;

use App\Entity\Reports;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reports>
 */
class ReportsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reports::class);
    }

    public function findReportsByTripId(string $tripId): array
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select('r')
            ->addSelect('SUM(CASE WHEN rc.isGood = 1 THEN 1 ELSE 0 END) as positiveCount')
            ->addSelect('SUM(CASE WHEN rc.isGood = 0 THEN 1 ELSE 0 END) as negativeCount')
            ->leftJoin('r.reportsCount', 'rc')
            ->where('r.tripId = :tripId')
            ->setParameter('tripId', $tripId)
            ->groupBy('r.id');

        return $qb->getQuery()->getArrayResult();
    }

    public function addReport(array $data): void
    {
        $em = $this->getEntityManager();

        $report = new Reports();

        $report->setTripId($data['tripId']);
        $report->setType($data['type']);
        $report->setUserId($data['userId']);
        $report->setReportLat($data['reportLat']);
        $report->setReportLon($data['reportLon']);
        $report->setDescription($data['description']);
        if(!empty($data['delayMinutes'])) $report->setDelayMinutes($data['delayMinutes']);

        $em->persist($report);
        $em->flush();

        $this->confirmReport($report);
    }

    public function updateReport(Reports $report, array $data): void
    {
        $em = $this->getEntityManager();

        if (!empty($data['type'])) $report->setType($data['type']);
        if (!empty($data['description'])) $report->setDescription($data['description']);

        $em->flush();
    }

    public function findReportById(int $id): ?Reports
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function confirmReport(Reports $report): void
    {
        $em = $this->getEntityManager();

        $connection = $em->getConnection();
        $connection->insert('reports_count', [
            'report_id' => $report->getId(),
            'is_good' => 1
        ]);
    }

    public function disproveReport(Reports $report): void
    {
        $em = $this->getEntityManager();

        $connection = $em->getConnection();
        $connection->insert('reports_count', [
            'report_id' => $report->getId(),
            'is_good' => 0
        ]);
    }

    public function deleteReport(Reports $report): void
    {
        $em = $this->getEntityManager();
        $em->remove($report);
        $em->flush();
    }
}
