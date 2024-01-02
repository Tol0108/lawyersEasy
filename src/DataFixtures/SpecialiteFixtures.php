<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Specialite;

class SpecialiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $specialites = [
            'Droit des assurances',
            'Droit des bancaire et boursier',
            'Droit des commercial',
            'Droit des crédit et de la consommation',
            'Droit des enfants',
            'Droit de l environnement',
            'Droit des etrangers',
            'Droit de la famille',
            'Droit des fiscal',
            'Droit des immobilier',
            'Droit pénal',
            'Droit public',
            'Droit de la santé',
            'Droit des sociétés',
            'Droit des sport',
            'Droit des transport',
            'Droit du travail',
            'Droit de la protection des donées personnelles',
            
        ];

        foreach ($specialites as $nom) {
            $specialite = new Specialite();
            $specialite->setNom($nom);

            $manager->persist($specialite);
        }

        $manager->flush();
    }
}
