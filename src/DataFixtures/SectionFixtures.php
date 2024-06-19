<?php

namespace App\DataFixtures;

use App\Entity\Section;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class SectionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 2; $i++) {
            $section = new Section();
            $section->setName($faker->word);
            $section->setDescription($faker->paragraph);
            $section->setClub($this->getReference('club_' . rand(0, 2)));
            $manager->persist($section);

            $this->addReference('section_' . $i, $section);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClubFixtures::class,
        ];
    }
}
