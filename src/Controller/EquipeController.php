<?php
// src/Controller/EquipeController.php
namespace App\Controller;

use App\Entity\Game;
use App\Entity\Equipe;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\EquipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        // Récupérer uniquement les matchs à domicile
        $games = $equipe->getHomeGames()->toArray();

        // graphique
        $dates = [];
        $homeScores = [];
        $awayScores = [];
        $wins = 0;
        $totalGames = count($games);

        // Parcourt chaque équipe dans $games
        foreach ($games as $game) {
            // ajout des données dans les tableaux
            $dates[] = $game->getDateTime()->format('d/m/Y');
            $homeScores[] = $game->getScoreHome();
            $awayScores[] = $game->getScoreAway();

            // Calcul des victoires
            if ($game->getScoreHome() > $game->getScoreAway()) {
                $wins++;
            }
        }

        $winrate = $totalGames > 0 ? ($wins / $totalGames) * 100 : 0;

        // Création du graphique Type Line
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            // Les dates
            'labels' => $dates,
            // les Scores par équipe
            'datasets' => [
                [
                    'label' => 'Score à domicile',
                    'backgroundColor' => 'rgb(75, 192, 192)',
                    'borderColor' => 'rgb(75, 192, 192)',
                    'data' => $homeScores,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Score à l\'extérieur',
                    'backgroundColor' => 'rgb(153, 102, 255)',
                    'borderColor' => 'rgb(153, 102, 255)',
                    'data' => $awayScores,
                    'tension' => 0.4,
                ],
            ],
        ]);

        // Options du graphique
        $chart->setOptions([
            'maintainAspectRatio' => false,
        ]);

        // rendu de la vue
        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
            'games' => $games,
            'chart' => $chart,
            'winrate' => $winrate,
        ]);
    }
}
