<?php

namespace App\DataFixtures;

use App\Entity\Users;
use App\Entity\Role;
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
            // Créez d'abord les rôles et persistez-les
    $roleAdmin = new Role();
    $roleAdmin->setName('ROLE_ADMIN');
    $manager->persist($roleAdmin);

    $roleAvocat = new Role();
    $roleAvocat->setName('ROLE_AVOCAT');
    $manager->persist($roleAvocat);

    $roleClient = new Role();
    $roleClient->setName('ROLE_CLIENT');
    $manager->persist($roleClient);

    // Créez ensuite un utilisateur et attribuez-lui un rôle
    

}
}
