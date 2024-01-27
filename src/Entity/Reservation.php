<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdTrait;
use App\Repository\ReservationRepository;
use App\Validator as Validator;
use DateTime;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[HasLifecycleCallbacks]
#[ApiResource(
    collectionOperations:[
        'get',
        'post' => [
            'openapi_context' => [
                'description' => 'Make a reservation for a truck on a parking spot : one reservation by week by truck on
                available parking spot. You cannot use a passed date or today date.',
            ]
        ]
    ],
    itemOperations: [
        'get',
        'patch' => ['denormalization_context' => ['groups' => ['Reservation:patch']]],
        'delete'
    ],
    denormalizationContext:['groups' => ['Reservation:write']],
    normalizationContext: ['groups' => ['Reservation:read']]
)]
#[Validator\ValidateReservation]
class Reservation
{
    use IdTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', unique: true)]
    #[Groups(['Reservation:read'])]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['Reservation:read', 'Reservation:write', 'Reservation:patch'])]
    #[Assert\Type('\DateTimeInterface')]
    #[Assert\GreaterThan(value: 'today 23:59:59')]
    private DateTime $dateReservation;

    #[ORM\ManyToOne(targetEntity: Truck::class, inversedBy: 'reservations')]
    #[Groups(['Reservation:read', 'Reservation:write'])]
    private Truck $truck;

    #[ORM\ManyToOne(targetEntity: ParkingSpot::class, inversedBy: 'reservations')]
    #[Groups(['Reservation:read', 'Reservation:write'])]
    private ParkingSpot $parkingSpot;

    #[ORM\Column(type: 'string')]
    #[Groups(['Reservation:read'])]
    private string $weekNumber;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['Reservation:read'])]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['Reservation:read'])]
    private DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * @return DateTime
     */
    public function getDateReservation(): DateTime
    {
        return $this->dateReservation;
    }

    /**
     * @param DateTime $dateReservation
     * @return $this
     */
    public function setDateReservation(DateTime $dateReservation): self
    {
        $this->dateReservation = $dateReservation;
        return $this;
    }

    /**
     * @return Truck
     */
    public function getTruck(): Truck
    {
        return $this->truck;
    }

    /**
     * @param Truck $truck
     * @return $this
     */
    public function setTruck(Truck $truck): self
    {
        $this->truck = $truck;
        return $this;
    }

    /**
     * @return ParkingSpot
     */
    public function getParkingSpot(): ParkingSpot
    {
        return $this->parkingSpot;
    }

    /**
     * @param ParkingSpot $parkingSpot
     * @return $this
     */
    public function setParkingSpot(ParkingSpot $parkingSpot): self
    {
        $this->parkingSpot = $parkingSpot;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getWeekNumber(): string
    {
        return $this->weekNumber;
    }

    /**
     * @param string $weekNumber
     * @return $this
     */
    public function setWeekNumber(string $weekNumber): Reservation
    {
        $this->weekNumber = $weekNumber;
        return $this;
    }

    /**
     * @return void
     */
    #[ORM\PrePersist]
    public function setDateReservationAndWeekNumber(): void
    {
        $weekNumber = $this->getDateReservation()->format('W');
        $this->setDateReservation($this->dateReservation->setTime(0,0));
        $this->setWeekNumber($weekNumber);
    }

    /**
     * @param PreUpdateEventArgs $args
     * @return void
     */
    #[ORM\PreUpdate]
    public function updatedEntity(PreUpdateEventArgs $args):void
    {
        if ($args->hasChangedField('dateReservation')) {
            $weekNumber = $this->getDateReservation()->format('W');
            if ($weekNumber !== $this->getWeekNumber()) {
                $this->setWeekNumber($weekNumber);
            }
        }
        $this->updatedAt = new DateTime();
    }
}
