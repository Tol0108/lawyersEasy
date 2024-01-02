<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Avocat;
use App\Entity\Users;
use App\Entity\Specialite;
use DateTime;

class AvocatFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            SpecialiteFixtures::class,
        ];
    }
    public function load(ObjectManager $manager): void
    {
        // Création d'une spécialité
        $specialite = $manager->getRepository(Specialite::class)->findOneBy([]);
        if (!$specialite) {
            $specialite = new Specialite();
            $specialite->setNom('Droit de Test');
            $manager->persist($specialite);
        }

        $manager->flush();

        
}
}