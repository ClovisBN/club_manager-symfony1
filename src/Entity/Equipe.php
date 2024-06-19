<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\EquipeRepository;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'equipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Section $section = null;

    #[ORM\ManyToOne(inversedBy: 'equipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $club = null;

    #[ORM\OneToMany(mappedBy: 'equipe', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'homeTeam', targetEntity: Game::class, orphanRemoval: true)]
    private Collection $homeGames;

    #[ORM\OneToMany(mappedBy: 'awayTeam', targetEntity: Game::class, orphanRemoval: true)]
    private Collection $awayGames;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->homeGames = new ArrayCollection();
        $this->awayGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): static
    {
        $this->section = $section;
        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): static
    {
        $this->club = $club;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setEquipe($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            if ($user->getEquipe() === $this) {
                $user->setEquipe(null);
            }
        }

        return $this;
    }

    public function getHomeGames(): Collection
    {
        return $this->homeGames;
    }

    public function addHomeGame(Game $game): static
    {
        if (!$this->homeGames->contains($game)) {
            $this->homeGames->add($game);
            $game->setHomeTeam($this);
        }

        return $this;
    }

    public function removeHomeGame(Game $game): static
    {
        if ($this->homeGames->removeElement($game)) {
            if ($game->getHomeTeam() === $this) {
                $game->setHomeTeam(null);
            }
        }

        return $this;
    }

    public function getAwayGames(): Collection
    {
        return $this->awayGames;
    }

    public function addAwayGame(Game $game): static
    {
        if (!$this->awayGames->contains($game)) {
            $this->awayGames->add($game);
            $game->setAwayTeam($this);
        }

        return $this;
    }

    public function removeAwayGame(Game $game): static
    {
        if ($this->awayGames->removeElement($game)) {
            if ($game->getAwayTeam() === $this) {
                $game->setAwayTeam(null);
            }
        }

        return $this;
    }
}
