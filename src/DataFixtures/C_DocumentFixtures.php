<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Document;

class DocumentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $document = new Document();
            $document->setNomDoc('Document ' . $i);
            $document->setChemin('path/to/document' . $i . '.pdf');
            
            // Associe le document à une réservation
            $reservation = $this->getReference('reservation_' . $i); // Utilise la référence créée dans ReservationsFixtures
            $document->setReservation($reservation);

            $manager->persist($document);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ReservationsFixtures::class,
        ];
    }
}
