<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Reservations;
use DateTime;

class ReservationsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
            
        $avocat = $this->getReference('avocat');

        // Création des réservations
        for ($i = 1; $i <= 10; $i++) {
            $reservation = new Reservations();
            $reservation->setDateReservation(new DateTime('now'));
            $reservation->setStatus('pending');
            $reservation->setUser($avocat);

            $reservation->setLegalAdvisor($avocat);

            $manager->persist($reservation);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

}
