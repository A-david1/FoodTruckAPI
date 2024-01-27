<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    normalizationContext: ['groups' => ['AccessDay:read']]
)]
class AccessDay
{
    private const MONDAY    = 'monday';
    private const TUESDAY   = 'tuesday';
    private const WEDNESDAY = 'wednesday';
    private const THURSDAY  = 'thursday';
    public const FRIDAY     = 'friday';
    private const SATURDAY  = 'saturday';
    private const SUNDAY    = 'sunday';

    /**
     * Keys are shown,because they must match the value return by the method format('w') from DateTime object
     */
    public const AVAILABLE_DAYS = [
        0 => self::SUNDAY,
        1 => self::MONDAY,
        2 => self::TUESDAY,
        3 => self::WEDNESDAY,
        4 => self::THURSDAY,
        5 => self::FRIDAY,
        6 => self::SATURDAY
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', unique: true)]
    #[Groups(['AccessDay:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 30, unique: true)]
    #[Groups(['AccessDay:read', 'ParkingSpot:read'])]
    private string $dayName;

    #[ORM\ManyToMany(targetEntity: ParkingSpot::class, mappedBy: 'accessDays')]
    #[Groups(['AccessDay:read'])]
    private Collection $parkingSpots;

    public function __construct()
    {
        $this->parkingSpots = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDayName(): string
    {
        return $this->dayName;
    }

    /**
     * @param string $dayName
     * @return $this
     */
    public function setDayName(string $dayName): self
    {
        $this->dayName = $dayName;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getParkingSpots(): Collection
    {
        return $this->parkingSpots;
    }

    /**
     * @param Collection $parkingSpot
     * @return $this
     */
    public function setParkingSpots(Collection $parkingSpot): self
    {
        $this->parkingSpots = $parkingSpot;
        return $this;
    }

    /**
     * @param ParkingSpot $parkingSpot
     * @return $this
     */
    public function addParkingSpot(ParkingSpot $parkingSpot): self
    {
        if (!$this->parkingSpots->contains($parkingSpot)) {
            $this->parkingSpots->add($parkingSpot);
        }
        return $this;
    }

    /**
     * @param Reservation $parkingSpot
     * @return $this
     */
    public function removeParkingSpot(Reservation $parkingSpot): self
    {
        if ($this->parkingSpots->contains($parkingSpot)) {
            $this->parkingSpots->removeElement($parkingSpot);
        }
        return $this;
    }
}
