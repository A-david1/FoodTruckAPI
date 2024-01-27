<?php

namespace App\Repository;

use App\Entity\ParkingSpot;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class ParkingSpotRepository extends EntityRepository
{
    /**
     * @param ParkingSpot $parkingSpot
     * @param string $dayName
     * @return bool
     * @throws NonUniqueResultException
     */
    public function isAvailableParkingSpot(ParkingSpot $parkingSpot, string $dayName): bool
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->join('p.accessDays', 'a')
            ->where($qb->expr()->eq('p.id', ':idParkingSpot'))
            ->andWhere($qb->expr()->eq('a.dayName', ':dayName'))
            ->setParameters([
                'idParkingSpot' => $parkingSpot->getId(),
                'dayName'       => $dayName,
            ])
            ->setMaxResults(1)
        ;
        return $qb->getQuery()->getOneOrNullResult() instanceof ParkingSpot;
    }
}
