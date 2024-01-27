<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdTrait;
use App\Repository\ParkingSpotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParkingSpotRepository::class)]
#[ApiResource(
    denormalizationContext: ['groups' => ['ParkingSpot:write']],
    normalizationContext: ['groups' => ['ParkingSpot:read']]
)]
class ParkingSpot
{
    use IdTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', unique: true)]
    #[Groups(['ParkingSpot:read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'The address is mandatory')]
    #[Groups(['ParkingSpot:write', 'ParkingSpot:read', 'Reservation:read'])]
    private string $address;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'The city is mandatory')]
    #[Groups(['ParkingSpot:write', 'ParkingSpot:read', 'Reservation:read'])]
    private string $city;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotBlank(message: 'The zipcode is mandatory')]
    #[Assert\Regex(pattern: ('/^[0-9]{5}/'))]
    #[Groups(['ParkingSpot:write', 'ParkingSpot:read', 'Reservation:read'])]
    private string $zipcode;

    #[ORM\OneToMany(mappedBy: 'parkingSpot', targetEntity: Reservation::class, cascade: ['remove'])]
    #[Groups(['ParkingSpot:read'])]
    private Collection $reservations;

    #[ORM\ManyToMany(targetEntity: AccessDay::class, inversedBy: 'parkingSpots')]
    #[Groups(['ParkingSpot:write', 'ParkingSpot:read'])]
    private Collection $accessDays;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->accessDays   = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): ParkingSpot
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    /**
     * @param string $zipcode
     * @return $this
     */
    public function setZipcode(string $zipcode): ParkingSpot
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    /**
     * @param Collection $reservations
     * @return $this
     */
    public function setReservations(Collection $reservations): self
    {
        $this->reservations = $reservations;
        return $this;
    }

    /**
     * @param Reservation $reservation
     * @return $this
     */
    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
        }
        return $this;
    }

    /**
     * @param Reservation $reservation
     * @return $this
     */
    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getAccessDays(): Collection
    {
        return $this->accessDays;
    }

    /**
     * @param Collection $accessDays
     * @return $this
     */
    public function setAccessDays(Collection $accessDays): self
    {
        $this->accessDays = $accessDays;
        return $this;
    }

    /**
     * @param AccessDay $accessDay
     * @return $this
     */
    public function addAccessDay(AccessDay $accessDay): self
    {
        if (!$this->accessDays->contains($accessDay)) {
            $this->accessDays->add($accessDay);
        }
        return $this;
    }

    /**
     * @param AccessDay $accessDay
     * @return $this
     */
    public function removeAccessDay(AccessDay $accessDay): self
    {
        if ($this->accessDays->contains($accessDay)) {
            $this->accessDays->removeElement($accessDay);
        }
        return $this;
    }
}
