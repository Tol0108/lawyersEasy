<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        // Utilisateur Admin
        $admin = new Users();
        $admin->setEmail('admin@example.com');
        $admin->setNom('AdminNom');
        $admin->setPrenom('AdminPrenom');
        $admin->setAdresse('123 Rue de l\'Admin');
        $admin->setTelephone('0123456789');
        $admin->setLicenceNumber(null);
        $admin->setCodePostal('1000');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpass'));
        $manager->persist($admin);

        // Utilisateur Avocat
        $avocat = new Users();
        $avocat->setEmail('avocat@example.com');
        $avocat->setNom('AvocatNom');
        $avocat->setPrenom('AvocatPrenom');
        $avocat->setAdresse('456 Rue de l\'Avocat');
        $avocat->setTelephone('0987654321');
        $avocat->setLicenceNumber('LIC0001');
        $avocat->setCodePostal('1050');
        $avocat->setRoles(['ROLE_AVOCAT']);
        $avocat->setPassword($this->passwordHasher->hashPassword($avocat, 'avocatpass'));
        $manager->persist($avocat);

        $this->addReference('avocat', $avocat);

        // Utilisateur Client
        $client = new Users();
        $client->setEmail('client@example.com');
        $client->setNom('ClientNom');
        $client->setPrenom('ClientPrenom');
        $client->setAdresse('789 Rue du Client');
        $client->setTelephone('0123456789');
        $client->setLicenceNumber(null);
        $client->setCodePostal('1200');
        $client->setRoles(['ROLE_CLIENT']);
        $client->setPassword($this->passwordHasher->hashPassword($client, 'clientpass'));
        $manager->persist($client);

        // Sauvegarder les utilisateurs dans la base de données
        $manager->flush();
    }
}
