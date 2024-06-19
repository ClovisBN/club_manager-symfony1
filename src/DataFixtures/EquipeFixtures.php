<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Equipe;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EquipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $equipe = new Equipe();
            $equipe->setName($faker->word);
            $equipe->setDescription($faker->paragraph);
            $equipe->setSection($this->getReference('section_' . rand(0, 1)));
            $equipe->setClub($equipe->getSection()->getClub());
            $manager->persist($equipe);

            $this->addReference('equipe_' . $i, $equipe);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SectionFixtures::class,
        ];
    }
}
