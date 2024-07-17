<?php

namespace App\Domain;

class Game
{
    private Team $team1;
    private Team $team2;
    private int $score1;
    private int $score2;

    public function __construct(Team $team1, Team $team2, int $score1, int $score2)
    {
        $this->team1 = $team1;
        $this->team2 = $team2;
        $this->score1 = $score1;
        $this->score2 = $score2;

        $this->updateTeams();
    }

    private function updateTeams(): void
    {
        $this->team1->scoreGoal($this->score1);
        $this->team2->scoreGoal($this->score2);
        $this->team1->concedeGoal($this->score2);
        $this->team2->concedeGoal($this->score1);
    }

    public function getWinner(): Team
    {
        if ($this->score1 > $this->score2) {
            return $this->team1;
        } elseif ($this->score2 > $this->score1) {
            return $this->team2;
        } else {
            return $this->team1->getPoints() > $this->team2->getPoints() ? $this->team1 : $this->team2;
        }
    }

    public function getTeam1(): Team
    {
        return $this->team1;
    }

    public function getTeam2(): Team
    {
        return $this->team2;
    }

    public function getScore1(): int
    {
        return $this->score1;
    }

    public function getScore2(): int
    {
        return $this->score2;
    }
}