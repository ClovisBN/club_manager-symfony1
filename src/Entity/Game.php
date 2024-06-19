<?php

namespace App\Entity;

use App\Entity\Equipe;
use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateTime = null;

    #[ORM\ManyToOne(inversedBy: 'homeGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipe $homeTeam = null;

    #[ORM\ManyToOne(inversedBy: 'awayGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipe $awayTeam = null;

    #[ORM\Column]
    private ?int $scoreHome = null;

    #[ORM\Column]
    private ?int $scoreAway = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->DateTime;
    }

    public function setDateTime(\DateTimeInterface $DateTime): static
    {
        $this->DateTime = $DateTime;
        return $this;
    }

    public function getHomeTeam(): ?Equipe
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Equipe $homeTeam): static
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    public function getAwayTeam(): ?Equipe
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Equipe $awayTeam): static
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

    public function getScoreHome(): ?int
    {
        return $this->scoreHome;
    }

    public function setScoreHome(int $scoreHome): static
    {
        $this->scoreHome = $scoreHome;
        return $this;
    }

    public function getScoreAway(): ?int
    {
        return $this->scoreAway;
    }

    public function setScoreAway(int $scoreAway): static
    {
        $this->scoreAway = $scoreAway;
        return $this;
    }
}
