<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new Users();
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpass'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $avocat = new Users();
        $avocat->setEmail('avocat@example.com');
        $avocat->setPassword($this->passwordHasher->hashPassword($avocat, 'avocatpass'));
        $avocat->setRoles(['ROLE_AVOCAT']);
        $manager->persist($avocat);

        $client = new Users();
        $client->setEmail('client@example.com');
        $client->setPassword($this->passwordHasher->hashPassword($client, 'clientpass'));
        $client->setRoles(['ROLE_CLIENT']);
        $manager->persist($client);

        $manager->flush();
    }
}

