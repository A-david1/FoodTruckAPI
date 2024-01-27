<?php

namespace App\DataFixtures;

use App\Entity\AccessDay;
use App\Entity\ParkingSpot;
use App\Entity\Truck;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $collection = new ArrayCollection();
        foreach (AccessDay::AVAILABLE_DAYS as $dayName) {
            $accessDay = (new AccessDay())->setDayName($dayName);
            $collection->add($accessDay);
            $manager->persist($accessDay);
        }

        for ($i = 1; $i < 8; $i++) {
            $parkingSpot = new ParkingSpot();
            $parkingSpot
                ->setAddress($i . ' rue du Loup')
                ->setCity('Bordeaux')
                ->setZipcode('33000')
                ->setAccessDays($collection)
            ;
            $manager->persist($parkingSpot);
        }


        for ($i = 1; $i < 4; $i++) {
            $truck = (new Truck())->setName('FoodTruck ' . $i);
            $manager->persist($truck);
        }

        $manager->flush();

        $parkingSpot = $manager->getRepository(ParkingSpot::class)->findOneBy(['address' => '1 rue du loup']);
        $parkingSpot->removeAccessDay($manager->getRepository(AccessDay::class)
            ->findOneBy(['dayName' => AccessDay::FRIDAY])
        );
        $manager->flush();
    }
}
