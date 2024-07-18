<?php

namespace App\Domain;

class PenaltyShootout
{
    private int $scoreTeam1;
    private int $scoreTeam2;

    public function __construct(Team $team1, Team $team2)
    {
        $this->scoreTeam1 = 0;
        $this->scoreTeam2 = 0;

        $this->performShootout($team1, $team2);
    }

    private function performShootout(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->scoreTeam1 += rand(0, 1);
            $this->scoreTeam2 += rand(0, 1);
        }

        while ($this->scoreTeam1 === $this->scoreTeam2) {
            $this->scoreTeam1 += rand(0, 1);
            $this->scoreTeam2 += rand(0, 1);
        }
    }

    public function getScoreTeam1(): int
    {
        return $this->scoreTeam1;
    }

    public function getScoreTeam2(): int
    {
        return $this->scoreTeam2;
    }

    public function getWinner(): int
    {
        return $this->scoreTeam1 > $this->scoreTeam2 ? 1 : 2;
    }
}