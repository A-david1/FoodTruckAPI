<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    denormalizationContext: ['groups' => ['Truck:write']],
    normalizationContext: ['groups' => ['Truck:read']],
)]
class Truck
{
    use IdTrait;

    #[ORM\Id]
    #[Groups(['Truck:read'])]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', unique: true)]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['Truck:read', 'Truck:write', 'Reservation:read'])]
    #[ApiProperty(identifier: false)]
    #[Assert\NotBlank(message: 'The name is mandatory')]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'truck',targetEntity: Reservation::class, cascade: ['remove'])]
    #[Groups(['Truck:read'])]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
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
}
