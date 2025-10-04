<?php

namespace App\Repository;

use App\Entity\Reports;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findReportsByTripId(string $tripId)
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.tripId = :tripId')
            ->setParameter('tripId', $tripId)
            ->getQuery()
            ->getResult();
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
        $report->setDate(new \DateTime(date('H:i:s \O\n d/m/Y')));

        $em->persist($report);
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

    public function confirmReport()
    {

    }

    //    /**
    //     * @return Reports[] Returns an array of Reports objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reports
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
