<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipeController extends AbstractController
{
    #[Route('/equipes', name: 'equipe_index')]
    public function index(EquipeRepository $equipeRepository): Response
    {
        $equipes = $equipeRepository->findAll();

        // Calcul des statistiques pour chaque équipe
        $stats = [];
        foreach ($equipes as $equipe) {
            $wins = 0;
            $losses = 0;
            $draws = 0;

            foreach ($equipe->getHomeGames() as $game) {
                if ($game->getScoreHome() > $game->getScoreAway()) {
                    $wins++;
                } elseif ($game->getScoreHome() < $game->getScoreAway()) {
                    $losses++;
                } else {
                    $draws++;
                }
            }

            foreach ($equipe->getAwayGames() as $game) {
                if ($game->getScoreAway() > $game->getScoreHome()) {
                    $wins++;
                } elseif ($game->getScoreAway() < $game->getScoreHome()) {
                    $losses++;
                } else {
                    $draws++;
                }
            }

            $stats[$equipe->getId()] = [
                'wins' => $wins,
                'losses' => $losses,
                'draws' => $draws,
            ];
        }

        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipes,
            'stats' => $stats,
        ]);
    }

    #[Route('/equipes/{id}', name: 'equipe_show')]
    public function show(Equipe $equipe): Response
    {
        $games = array_merge($equipe->getHomeGames()->toArray(), $equipe->getAwayGames()->toArray());

        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
            'games' => $games,
        ]);
    }
}
