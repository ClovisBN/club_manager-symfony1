<?php

// src/Controller/EquipeController.php
namespace App\Controller;

use App\Entity\Equipe;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

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
    public function show(Equipe $equipe, ChartBuilderInterface $chartBuilder): Response
    {
        $games = array_merge($equipe->getHomeGames()->toArray(), $equipe->getAwayGames()->toArray());

        $dates = [];
        $homeScores = [];
        $awayScores = [];

        foreach ($games as $game) {
            $dates[] = $game->getDateTime()->format('d/m/Y');
            $homeScores[] = $game->getScoreHome();
            $awayScores[] = $game->getScoreAway();
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Score à domicile',
                    'backgroundColor' => 'rgb(75, 192, 192)',
                    'borderColor' => 'rgb(75, 192, 192)',
                    'data' => $homeScores,
                ],
                [
                    'label' => 'Score à l\'extérieur',
                    'backgroundColor' => 'rgb(153, 102, 255)',
                    'borderColor' => 'rgb(153, 102, 255)',
                    'data' => $awayScores,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ]);

        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
            'games' => $games,
            'chart' => $chart,
        ]);
    }
}

