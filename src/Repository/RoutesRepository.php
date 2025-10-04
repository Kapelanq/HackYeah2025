<?php

namespace App\Repository;

use App\Entity\StopTimes;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class RoutesRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, StopTimes::class);
    }
    public function findAllTripStopsById(string $tripId): array
    {
        return $this->getEntityManager()
        ->createQuery(
            'SELECT s FROM stop_times WHERE trip_id = :tripId'
        )
        ->setParameter('tripId', $tripId)
            ->getResult();
    }
}
