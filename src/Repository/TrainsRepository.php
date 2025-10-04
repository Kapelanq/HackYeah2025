<?php

namespace App\Repository;

use App\Entity\Trains;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trains>
 */
class TrainsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trains::class);
    }

    public function getOneByTripId(string $tripId): ?Trains
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.tripId = :tripId')
            ->setParameter('tripId', $tripId)
            ->getQuery()
            ->getSingleResult();
    }
}
