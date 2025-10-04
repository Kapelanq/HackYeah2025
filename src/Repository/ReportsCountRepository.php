<?php

namespace App\Repository;

use App\Entity\Reports;
use App\Entity\ReportsCount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReportsCountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportsCount::class);
    }

    public function groupGetCount(Reports $report): array
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id), r.isGood')
            ->andWhere('r.report = :report')
            ->setParameter('report', $report)
            ->groupBy('r.isGood')
            ->getQuery()
            ->getArrayResult();
    }


}
