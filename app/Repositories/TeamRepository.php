<?php

namespace App\Repositories;

use App\Domain\Team;

class TeamRepository
{
    private array $teams = [];

    public function save(Team $team): void
    {
        $this->teams[] = $team;
    }

    public function findAll(): array
    {
        return $this->teams;
    }

    public function findByName(string $name): ?Team
    {
        foreach ($this->teams as $team) {
            if ($team->getName() === $name) {
                return $team;
            }
        }
        return null;
    }

    public function clear(): void
    {
        $this->teams = [];
    }
}
