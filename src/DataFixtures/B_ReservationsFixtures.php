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
        $client = $this->getReference('client');

        for ($i = 1; $i <= 10; $i++) {
            $reservation = new Reservations();
            $reservation->setDateReservation(new DateTime('now'));
            $reservation->setUser($client);

            $manager->persist($reservation);

            // Ajoute une référence à cette réservation pour l'utiliser dans les documents
            $this->addReference('reservation_' . $i, $reservation);
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
