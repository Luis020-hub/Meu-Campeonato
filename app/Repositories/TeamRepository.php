<?php

namespace App\Repositories;

use App\Models\Team;

class TeamRepository
{
    public function save(Team $team): void
    {
        $team->save();
    }

    public function findAll(): array
    {
        return Team::all()->toArray();
    }

    public function findByName(string $name): ?Team
    {
        return Team::where('name', $name)->first();
    }

    public function findOrCreateByName(string $name): Team
    {
        $team = $this->findByName($name);
        if (!$team) {
            $team = new Team(['name' => $name]);
            $this->save($team);
        }
        return $team;
    }

    public function clear(): void
    {
        Team::truncate();
    }
}