<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public const ROLE_USER_REFERENCE = 'role-user';
    public const ROLE_ADMIN_REFERENCE = 'role-admin';

    public function load(ObjectManager $manager): void
    {
        // Create the ROLE_USER role
        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');
        $manager->persist($roleUser);
        $this->addReference(self::ROLE_USER_REFERENCE, $roleUser);

        // Create the ROLE_ADMIN role
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $manager->persist($roleAdmin);
        $this->addReference(self::ROLE_ADMIN_REFERENCE, $roleAdmin);

        $manager->flush();
    }
}
