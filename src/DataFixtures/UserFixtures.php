<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Users;
use App\Entity\Role;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private $passwordHasher ;

    public function __construct(UserPasswordHasherInterface  $passwordHasher)
    {
        $this->passwordHasher  = $passwordHasher ;
    }

    public function load(ObjectManager $manager): void
    {

        $roleUser = new Role();
        $roleUser->setName('ROLE_USER'); // Assurez-vous que cela correspond à votre définition de rôle
    
        $manager->persist($roleUser);
        $manager->flush();
        
        $this->setReference('role-user', $roleUser);

        $admin = new Users();
        $admin->setLogin('admin');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, '1234'));
        $admin->setNom('Admin');
        $admin->setPrenom('Admin');
        $admin->setEmail('admin@admin.be');
        $admin->setLangue('FR');
        $admin->setStatus('actif');
        $admin->setType('admin');
        $admin->setIsActive(true);

        $adminRole = $manager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_ADMIN']);
        if (!$adminRole) {
            // Créez le rôle admin si non existant
            $adminRole = new Role();
            $adminRole->setName('ROLE_ADMIN');
            $manager->persist($adminRole);
        }

        // Créez le rôle client s'il n'existe pas encore
        $roleClient = $manager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_CLIENT']);
        if (!$roleClient) {
            $roleClient = new Role();
            $roleClient->setName('ROLE_CLIENT');
            $manager->persist($roleClient);
        }

        // Créez le rôle avocat s'il n'existe pas encore
        $roleAvocat = $manager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_AVOCAT']);
        if (!$roleAvocat) {
            $roleAvocat = new Role();
            $roleAvocat->setName('ROLE_AVOCAT');
            $manager->persist($roleAvocat);
        }

        $admin->setRole($adminRole);

        $manager->persist($admin);

        $tarik = new Users();
        $tarik->setLogin('tarik.lohiss');
        $tarik->setPassword($this->passwordHasher ->hashPassword($tarik, '1234'));
        $tarik->setNom('Lohiss');
        $tarik->setPrenom('tarik');
        $tarik->setEmail('tarik.lohiss@hotmail.com');
        $tarik->setTelephone(0477123456);
        $tarik->setLangue('FR');
        $tarik->setStatus('actif');
        $tarik->setType('avocat');
        $tarik->setIsActive(true);
        $tarik->setRole($adminRole);
        $manager->persist($tarik);

        $alice = new Users();
        $alice->setLogin('alice.dubois');
        $alice->setPassword($this->passwordHasher ->hashPassword($alice, '1234'));
        $alice->setNom('Dubois');
        $alice->setPrenom('Alice');
        $alice->setEmail('avocat@avocat.be');
        $alice->setTelephone(1234567890);
        $alice->setLangue('FR');
        $alice->setStatus('actif');
        $alice->setType('client');
        $alice->setIsActive(true);
        $alice->setRole($roleAvocat);
        $manager->persist($alice);

        $bruno = new Users();
        $bruno->setLogin('bruno.leroy');
        $bruno->setPassword($this->passwordHasher ->hashPassword($bruno, '1234'));
        $bruno->setNom('Leroy');
        $bruno->setPrenom('Bruno');
        $bruno->setEmail('client@client.be');
        $bruno->setTelephone(2345678901);
        $bruno->setLangue('EN');
        $bruno->setStatus('inactif');
        $bruno->setType('client');
        $bruno->setIsActive(false);
        $bruno->setRole($roleClient);
        $manager->persist($bruno);


        

        $manager->flush();
    }
}
