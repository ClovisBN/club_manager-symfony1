<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setName('John Doe');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $user->setRole($this->getReference(RoleFixtures::ROLE_USER_REFERENCE)); // Set the role to ROLE_USER
        $manager->persist($user);

        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setName('Rick Doe');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password123'));
        $admin->setRole($this->getReference(RoleFixtures::ROLE_ADMIN_REFERENCE)); // Set the role to ROLE_ADMIN
        $manager->persist($admin);

        // Create 50 random users
        for ($i = 0; $i < 50; $i++) {
            $randomUser = new User();
            $randomUser->setEmail($faker->unique()->email);
            $randomUser->setName($faker->name);
            $randomUser->setPassword($this->passwordHasher->hashPassword($randomUser, 'password123'));
            $randomUser->setRole($this->getReference(RoleFixtures::ROLE_USER_REFERENCE)); // Set the role to ROLE_USER
            $manager->persist($randomUser);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            RoleFixtures::class,
        ];
    }
}
