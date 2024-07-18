<?php

namespace App\Domain;

class Game
{
    private Team $host;
    private Team $guest;
    private int $score1;
    private int $score2;
    private ?int $penaltyScore1 = null;
    private ?int $penaltyScore2 = null;
    private ?Team $winner = null;
    private ?Team $loser = null;

    public function __construct(Team $host, Team $guest, int $score1, int $score2)
    {
        $this->host = $host;
        $this->guest = $guest;
        $this->score1 = $score1;
        $this->score2 = $score2;

        $this->host->addGoalsScored($score1);
        $this->host->addGoalsConceded($score2);
        $this->guest->addGoalsScored($score2);
        $this->guest->addGoalsConceded($score1);

        if ($score1 > $score2) {
            $this->host->addPoints($score1);
            $this->winner = $host;
            $this->loser = $guest;
        } elseif ($score1 < $score2) {
            $this->guest->addPoints($score2);
            $this->winner = $guest;
            $this->loser = $host;
        }
    }

    public function setPenalties(int $penaltyScore1, int $penaltyScore2): void
    {
        $this->penaltyScore1 = $penaltyScore1;
        $this->penaltyScore2 = $penaltyScore2;

        if ($penaltyScore1 > $penaltyScore2) {
            $this->winner = $this->host;
            $this->loser = $this->guest;
        } else {
            $this->winner = $this->guest;
            $this->loser = $this->host;
        }
    }

    public function getWinner(): Team
    {
        return $this->winner;
    }

    public function toArray(): array
    {
        $gameData = [
            'host' => [
                'name' => $this->host->getName(),
                'goals' => $this->score1
            ],
            'guest' => [
                'name' => $this->guest->getName(),
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
                'host' => $this->penaltyScore1,
                'guest' => $this->penaltyScore2
            ];
        }

        return $gameData;
    }
}