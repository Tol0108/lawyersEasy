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
        $admin->setCodePostal('1000');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, '123'));
        $manager->persist($admin);

        // Utilisateur Client
        $client = new Users();
        $client->setEmail('client@example.com');
        $client->setNom('ClientNom');
        $client->setPrenom('ClientPrenom');
        $client->setAdresse('789 Rue du Client');
        $client->setTelephone('0123456789');
        $client->setCodePostal('1200');
        $client->setRoles(['ROLE_USER']);
        $client->setPassword($this->passwordHasher->hashPassword($client, '123'));
        $specialite = $this->getReference('specialite_1');  

        $manager->persist($client);

        // Sauvegarder les utilisateurs dans la base de données
        $manager->flush();
    }
}
