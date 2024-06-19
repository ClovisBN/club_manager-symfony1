<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\DataFixtures\RoleFixtures;
use App\DataFixtures\EquipeFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
        $user->setRole($this->getReference(RoleFixtures::ROLE_USER_REFERENCE));
        $user->setEquipe($this->getReference('equipe_0'));
        $manager->persist($user);

        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setName('Rick Doe');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password123'));
        $admin->setRole($this->getReference(RoleFixtures::ROLE_ADMIN_REFERENCE));
        $admin->setEquipe($this->getReference('equipe_1'));
        $manager->persist($admin);

        for ($i = 0; $i < 100; $i++) {
            $randomUser = new User();
            $randomUser->setEmail($faker->unique()->email);
            $randomUser->setName($faker->name);
            $randomUser->setPassword($this->passwordHasher->hashPassword($randomUser, 'password123'));
            $randomUser->setRole($this->getReference(RoleFixtures::ROLE_USER_REFERENCE));
            $randomUser->setEquipe($this->getReference('equipe_' . rand(0, 19)));
            $manager->persist($randomUser);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            RoleFixtures::class,
            EquipeFixtures::class,
        ];
    }
}
