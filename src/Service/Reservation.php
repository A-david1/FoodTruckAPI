<?php

namespace App\Service;

use App\Entity\AccessDay;
use App\Entity\ParkingSpot;
use App\Entity\Reservation as ReservationEntity;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class Reservation
{
    private ObjectManager $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    /**
     * @param ReservationEntity $reservation
     * @param string $weekNumber
     * @return array
     */
    public function authorizedReservations(ReservationEntity $reservation, string $weekNumber): array
    {
        return $this->em->getRepository(ReservationEntity::class)
            ->findExistingReservations($reservation,$weekNumber)
        ;
    }

    /**
     * @param ReservationEntity $reservation
     * @param string $weekNumber
     * @return bool
     */
    public function alreadyHasReservation(ReservationEntity $reservation, string $weekNumber): bool
    {
        return $this->em->getRepository(ReservationEntity::class)
            ->hasAlreadyAReservation($reservation, $weekNumber)
        ;
    }

    /**
     * @param ReservationEntity $reservation
     * @return bool
     */
    public function accessibleParkinSpotForReservation(ReservationEntity $reservation): bool
    {
        $dayName = AccessDay::AVAILABLE_DAYS[$reservation->getDateReservation()->format('w')];
        return $this->em->getRepository(ParkingSpot::class)
            ->isAvailableParkingSpot($reservation->getParkingSpot(), $dayName)
        ;
    }
}
