<?php

namespace App\Domain;

class Game
{
    private Team $team1;
    private Team $team2;
    private int $score1;
    private int $score2;
    private ?int $penaltyScore1 = null;
    private ?int $penaltyScore2 = null;
    private ?Team $winner = null;
    private ?Team $loser = null;

    public function __construct(Team $team1, Team $team2, int $score1, int $score2)
    {
        $this->team1 = $team1;
        $this->team2 = $team2;
        $this->score1 = $score1;
        $this->score2 = $score2;

        $this->team1->addGoalsScored($score1);
        $this->team1->addGoalsConceded($score2);
        $this->team2->addGoalsScored($score2);
        $this->team2->addGoalsConceded($score1);

        if ($score1 > $score2) {
            $this->team1->addPoints($score1);
            $this->winner = $team1;
            $this->loser = $team2;
        } elseif ($score1 < $score2) {
            $this->team2->addPoints($score2);
            $this->winner = $team2;
            $this->loser = $team1;
        }
    }

    public function setPenalties(int $penaltyScore1, int $penaltyScore2): void
    {
        $this->penaltyScore1 = $penaltyScore1;
        $this->penaltyScore2 = $penaltyScore2;

        if ($penaltyScore1 > $penaltyScore2) {
            $this->winner = $this->team1;
            $this->loser = $this->team2;
        } else {
            $this->winner = $this->team2;
            $this->loser = $this->team1;
        }
    }

    public function getWinner(): Team
    {
        return $this->winner;
    }

    public function toArray(): array
    {
        $gameData = [
            'team1' => [
                'name' => $this->team1->getName(),
                'goals' => $this->score1
            ],
            'team2' => [
                'name' => $this->team2->getName(),
                'goals' => $this->score2
            ],
            'winner' => [
                'name' => $this->winner->getName()
            ],
            'loser' => [
                'name' => $this->loser->getName()
            ]
        ];

        if (!is_null($this->penaltyScore1) && !is_null($this->penaltyScore2)) {
            $gameData['penalties'] = [
                'team1' => $this->penaltyScore1,
                'team2' => $this->penaltyScore2
            ];
        }

        return $gameData;
    }
}