<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            [
                'email' => 'patinesmtmoi@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'EG50!Gfh',
                'is_verified' => true,
            ],
        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);

            // Hash du mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);

            $user->setVerified($userData['is_verified']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
