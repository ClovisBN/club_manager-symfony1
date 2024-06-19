<?php

namespace App\DataFixtures;

use App\Entity\Club;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClubFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 3; $i++) {
            $club = new Club();
            $club->setName($faker->company);
            $club->setDescription($faker->paragraph);
            $manager->persist($club);

            $this->addReference('club_' . $i, $club);
        }

        $manager->flush();
    }
}
