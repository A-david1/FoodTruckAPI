<?php

namespace App\Validator;

use App\Entity\Reservation;
use App\Service\Reservation as ReservationService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use UnexpectedValueException;

class ValidateReservationValidator extends ConstraintValidator
{
    private const ERROR_MESSAGE_PARKING_SPOT_TAKEN = 'This parking spot is already reserved';
    private const ERROR_MESSAGE_ALREADY_EXIST = 'Already has a reservation this week';
    private const ERROR_MESSAGE_PARKING_SPOT_NOT_AVAILABLE = 'This parking spot is not available this day';

    private ReservationService $reservationService;

    public function __construct(ReservationService $reservation)
    {
        $this->reservationService = $reservation;
    }

    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (false === $value instanceof Reservation) {
            throw new UnexpectedValueException($value, Reservation::class);
        }

        $dateReservation = $value->getDateReservation();
        $weekNumber = $dateReservation->format('W');
        $dateReservation->setTime(0,0);

        if ($this->reservationService->alreadyHasReservation($value, $weekNumber)) {
            $this->context
                ->buildViolation(self::ERROR_MESSAGE_ALREADY_EXIST)
                ->addViolation()
            ;
            return;
        }

        $alreadyExistReservations = $this->reservationService->authorizedReservations($value, $weekNumber);
        if (!empty($alreadyExistReservations)) {
            $this->context
                ->buildViolation(self::ERROR_MESSAGE_PARKING_SPOT_TAKEN)
                ->addViolation()
            ;

        } else  {
            if (false === $this->reservationService->accessibleParkinSpotForReservation($value)) {
                $this->context
                    ->buildViolation(self::ERROR_MESSAGE_PARKING_SPOT_NOT_AVAILABLE)
                    ->addViolation()
                ;
            }
        }
    }
}
