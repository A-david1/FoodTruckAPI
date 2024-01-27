<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class ReservationRepository extends EntityRepository
{
    /**
     * @param Reservation $reservation
     * @param string $weekNumber
     * @return array
     */
    public function findExistingReservations(Reservation $reservation, string $weekNumber): array
    {
        $qb = $this->createQueryBuilder('r');
        $qb
            ->join('r.parkingSpot', 'p')
            ->where($qb->expr()->eq('r.weekNumber', ':weekNumber'))
            ->andWhere($qb->expr()->eq('p.id', ':idParkingSpot'))
            ->andWhere($qb->expr()->eq('r.dateReservation', ':dateReservation'))
            ->setParameters([
                'weekNumber'        => $weekNumber,
                'dateReservation'   => $reservation->getDateReservation(),
                'idParkingSpot'     => $reservation->getParkingSpot()->getId(),
            ])
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Reservation $reservation
     * @param string $weekNumber
     * @return bool
     * @throws NonUniqueResultException
     */
    public function hasAlreadyAReservation(Reservation $reservation, string $weekNumber): bool
    {
        $qb = $this->createQueryBuilder('r');
        $qb
            ->join('r.truck', 't')
            ->where($qb->expr()->eq('r.weekNumber', ':weekNumber'))
            ->andWhere($qb->expr()->eq('t.id', ':idTruck'))
            ->setParameters([
                'weekNumber'    => $weekNumber,
                'idTruck'       => $reservation->getTruck()->getId(),
            ])
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult() instanceof Reservation;
    }
}
