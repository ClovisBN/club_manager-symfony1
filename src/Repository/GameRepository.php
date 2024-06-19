<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function countWinsByTeam(int $teamId): int
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->where('g.homeTeam = :teamId AND g.scoreHome > g.scoreAway')
            ->orWhere('g.awayTeam = :teamId AND g.scoreAway > g.scoreHome')
            ->setParameter('teamId', $teamId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countLossesByTeam(int $teamId): int
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->where('g.homeTeam = :teamId AND g.scoreHome < g.scoreAway')
            ->orWhere('g.awayTeam = :teamId AND g.scoreAway < g.scoreHome')
            ->setParameter('teamId', $teamId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countDrawsByTeam(int $teamId): int
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->where('g.homeTeam = :teamId AND g.scoreHome = g.scoreAway')
            ->orWhere('g.awayTeam = :teamId AND g.scoreHome = g.scoreAway')
            ->setParameter('teamId', $teamId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
