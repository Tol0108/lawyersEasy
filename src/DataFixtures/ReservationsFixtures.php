<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Reservations;
use App\Entity\Avocat;
use DateTime;

class ReservationsFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            AvocatFixtures::class,
        ];
    }
    public function load(ObjectManager $manager): void
    {
            
                $avocat = new Avocat();
                

        // Création des réservations
        for ($i = 1; $i <= 10; $i++) {
            $reservation = new Reservations();
            $reservation->setDateReservation(new DateTime('now'));
            $reservation->setStatus('pending');
            $reservation->setAvocat($avocat); // Associer l'avocat

            $manager->persist($reservation);
            }

            if (!$avocat) {
                throw new \Exception("Aucun avocat trouvé pour l'id 1");

            $manager->flush();
        }
    }

}
