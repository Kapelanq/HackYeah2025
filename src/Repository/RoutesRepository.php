<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class RoutesRepository extends EntityRepository
{
    public function findAllTripStopsById(string $tripId): array
    {

    }
}
