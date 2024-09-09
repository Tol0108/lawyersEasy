<?php

namespace App\DataFixtures;

use App\Entity\Document;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DocumentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $documents = [
            ['nom_doc' => 'Droit du travail', 'sujet_doc' => 'Probleme au travail', 'chemin' => 'documents/droit_du_travail.pdf'],
            ['nom_doc' => 'Droit de la famille', 'sujet_doc' => 'Probleme familliale', 'chemin' => 'documents/droit_de_la_famille.pdf'],
            ['nom_doc' => 'Divorce', 'sujet_doc' => 'separation entre deux personnes', 'chemin' => 'documents/divorce.pdf'],
            ['nom_doc' => 'Droit des affaires', 'sujet_doc' => 'Probleme de societes', 'chemin' => 'documents/droit_des_affaires.pdf'],
            ['nom_doc' => 'Droit des etrangers', 'sujet_doc' => 'Probleme de papier', 'chemin' => 'documents/droit_des_etrangers.pdf'],
            ['nom_doc' => 'Droit social', 'sujet_doc' => 'Probleme social', 'chemin' => 'documents/droit_social.pdf'],
            ['nom_doc' => 'Droit penal', 'sujet_doc' => 'Probleme pénal', 'chemin' => 'documents/droit_penal.pdf'],
            ['nom_doc' => 'Aide Juridique', 'sujet_doc' => 'Question juridique', 'chemin' => 'documents/aide_juridique.pdf'],
            ['nom_doc' => 'Droit Civil', 'sujet_doc' => 'Probleme de droit civil', 'chemin' => 'documents/droit_civil.pdf'],
        ];
        
        foreach ($documents as $record) {
            $document = new Document();
            $document->setNomDoc($record['nom_doc']);
            $document->setSujetDoc($record['sujet_doc']);
            $document->setChemin($record['chemin']); 

            $manager->persist($document);
            $this->addReference(
                $record['nom_doc']."-".$record['sujet_doc'],
                $document
            );
        }

        $manager->flush();
    }
}

