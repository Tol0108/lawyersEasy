<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Users;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $userAdmin = new Users();
        $userAdmin->setEmail('admin@admin.be');
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userAdmin, '1234'));
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $manager->persist($userAdmin);

        $userClient = new Users();
        $userClient->setEmail('client@client.be');
        $userClient->setPassword($this->passwordHasher->hashPassword($userClient, '1234'));
        $userClient->setRoles(['ROLE_CLIENT']);
        $manager->persist($userClient);

        $userAvocat = new Users();
        $userAvocat->setEmail('avocat@avocat.be');
        $userAvocat->setPassword($this->passwordHasher->hashPassword($userAvocat, '1234'));
        $userAvocat->setRoles(['ROLE_AVOCAT']);
        $manager->persist($userAvocat);

        $manager->flush();
    }
}
