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

    public function addPoints(int $points): void
    {
        $this->points += $points;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function addGoalsScored(int $goals): void
    {
        $this->goalsScored += $goals;
    }

    public function getGoalsScored(): int
    {
        return $this->goalsScored;
    }

    public function addGoalsConceded(int $goals): void
    {
        $this->goalsConceded += $goals;
    }

    public function getGoalsConceded(): int
    {
        return $this->goalsConceded;
    }
}