<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findBy([], ['DateTime' => 'ASC'], 20);

        return $this->render('home/index.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/api/games', name: 'api_games')]
    public function getGames(GameRepository $gameRepository): JsonResponse
    {
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 20;
        $offset = ($page - 1) * $limit;

        $games = $gameRepository->findBy([], ['DateTime' => 'ASC'], $limit, $offset);
        $totalGames = $gameRepository->count([]);

        $data = array_map(function ($game) {
            return [
                'dateTime' => $game->getDateTime()->format('d/m/Y H:i'),
                'homeTeam' => $game->getHomeTeam()->getName(),
                'awayTeam' => $game->getAwayTeam()->getName(),
                'scoreHome' => $game->getScoreHome(),
                'scoreAway' => $game->getScoreAway(),
                'id' => $game->getId(),
            ];
        }, $games);

        return new JsonResponse([
            'games' => $data,
            'hasMore' => $offset + count($games) < $totalGames,
        ]);
    }
}
