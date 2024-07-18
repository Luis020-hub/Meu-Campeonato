<?php

namespace App\Domain;

class Game
{
    private Team $team1;
    private Team $team2;
    private int $score1;
    private int $score2;
    private ?PenaltyShootout $penaltyShootout;

    public function __construct(Team $team1, Team $team2, int $score1, int $score2)
    {
        $this->team1 = $team1;
        $this->team2 = $team2;
        $this->score1 = $score1;
        $this->score2 = $score2;
        $this->penaltyShootout = null;

        $this->updateTeams();
        $this->checkForPenalties();
    }

    private function updateTeams(): void
    {
        $this->team1->scoreGoal($this->score1);
        $this->team2->scoreGoal($this->score2);
        $this->team1->concedeGoal($this->score2);
        $this->team2->concedeGoal($this->score1);
    }

    private function checkForPenalties(): void
    {
        if ($this->score1 === $this->score2) {
            $this->penaltyShootout = new PenaltyShootout($this->team1, $this->team2);
        }
    }

    public function getWinner(): Team
    {
        if ($this->penaltyShootout) {
            return $this->penaltyShootout->getWinner() === 1 ? $this->team1 : $this->team2;
        }

        if ($this->score1 > $this->score2) {
            return $this->team1;
        } else {
            return $this->team2;
        }
    }

    public function getLoser(): Team
    {
        return $this->getWinner() === $this->team1 ? $this->team2 : $this->team1;
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

    public function toArray(): array
    {
        $result = [
            'team1' => $this->team1->getName(),
            'team2' => $this->team2->getName(),
            'score1' => $this->score1,
            'score2' => $this->score2,
            'winner' => $this->getWinner()->getName(),
            'loser' => $this->getLoser()->getName(),
        ];

        if ($this->penaltyShootout) {
            $result['penalties'] = [
                'team1' => $this->penaltyShootout->getScoreTeam1(),
                'team2' => $this->penaltyShootout->getScoreTeam2()
            ];
        }

        return $result;
    }
}