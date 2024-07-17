<?php

namespace App\Domain;

class Team
{
    private string $name;
    private int $points = 0;
    private int $goalsScored = 0;
    private int $goalsConceded = 0;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getGoalsScored(): int
    {
        return $this->goalsScored;
    }

    public function getGoalsConceded(): int
    {
        return $this->goalsConceded;
    }

    public function scoreGoal(int $goals): void
    {
        $this->goalsScored += $goals;
        $this->points += $goals;
    }

    public function concedeGoal(int $goals): void
    {
        $this->goalsConceded += $goals;
        $this->points -= $goals;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'points' => $this->points,
            'goalsScored' => $this->goalsScored,
            'goalsConceded' => $this->goalsConceded,
        ];
    }
}