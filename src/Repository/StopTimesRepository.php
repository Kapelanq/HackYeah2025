<?php

namespace App\Repository;

use App\Entity\StopTimes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Stops;

/**
 * @extends ServiceEntityRepository<StopTimes>
 */
class StopTimesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StopTimes::class);
    }

    public function findStopTimesByTripId(string $tripId): array
    {
        return $this->createQueryBuilder('t')
            ->select('s.stopName, s.stopLat, s.stopLon, t')
            ->join(Stops::class, 's', 'WITH', 's.stopId = t.stopId')
            ->andWhere('t.tripId = :tripId')
            ->setParameter('tripId', $tripId)
            ->getQuery()
            ->getResult();
    }

    public function findAloneStopTimesByTripId(string $tripId): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.tripId = :tripId')
            ->setParameter('tripId', $tripId)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return StopTimes[] Returns an array of StopTimes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?StopTimes
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
