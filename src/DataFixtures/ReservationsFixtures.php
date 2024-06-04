<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Reservations;
use DateTime;

class ReservationsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
            
        $userAvocat = $this->getReference('user-avocat');

        // Création des réservations
        for ($i = 1; $i <= 10; $i++) {
            $reservation = new Reservations();
            $reservation->setDateReservation(new DateTime('now'));
            $reservation->setStatus('pending');
            $reservation->setUser($userAvocat); // Associer l'utilisateur comme avocat

            $manager->persist($reservation);
        }

        $manager->flush();
    }

}
