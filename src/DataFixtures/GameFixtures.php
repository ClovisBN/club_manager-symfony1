<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Repository\EquipeRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GameFixtures extends Fixture implements DependentFixtureInterface
{
    private $equipeRepository;

    public function __construct(EquipeRepository $equipeRepository)
    {
        $this->equipeRepository = $equipeRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $equipes = $this->equipeRepository->findAll();
        $maxMatchesPerTeam = 30;

        $matchCounts = array_fill_keys(array_map(fn($equipe) => $equipe->getId(), $equipes), 0);

        foreach ($equipes as $homeTeam) {
            foreach ($equipes as $awayTeam) {
                if ($homeTeam !== $awayTeam &&
                    $homeTeam->getSection() !== $awayTeam->getSection() &&
                    $homeTeam->getSection()->getClub() !== $awayTeam->getSection()->getClub() &&
                    $matchCounts[$homeTeam->getId()] < $maxMatchesPerTeam &&
                    $matchCounts[$awayTeam->getId()] < $maxMatchesPerTeam) {

                    $game = new Game();
                    $game->setHomeTeam($homeTeam);
                    $game->setAwayTeam($awayTeam);
                    $game->setDateTime(new \DateTime());
                    $game->setScoreHome(rand(0, 5));
                    $game->setScoreAway(rand(0, 5));

                    $manager->persist($game);

                    $matchCounts[$homeTeam->getId()]++;
                    $matchCounts[$awayTeam->getId()]++;
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EquipeFixtures::class,
        ];
    }
}
