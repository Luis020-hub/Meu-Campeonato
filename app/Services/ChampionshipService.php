<?php

namespace App\Services;

use App\Domain\Team;
use App\Domain\Game;
use App\Repositories\TeamRepository;
use App\Services\ScoreService;

class ChampionshipService
{
    private TeamRepository $teamRepository;
    private ScoreService $scoreService;

    public function __construct(TeamRepository $teamRepository, ScoreService $scoreService)
    {
        $this->teamRepository = $teamRepository;
        $this->scoreService = $scoreService;
    }

    public function simulateChampionship(array $teams): array
    {
        $this->teamRepository->clear();
        foreach ($teams as $team) {
            $this->teamRepository->save(new Team($team));
        }

        $teams = $this->teamRepository->findAll();
        $rounds = [
            'quarterfinals' => $this->simulateRound($teams, 4),
            'semifinals' => $this->simulateRound($teams, 2),
            'final' => $this->simulateRound($teams, 1)
        ];

        return $rounds;
    }

    private function simulateRound(array &$teams, int $numGames): array
    {
        $games = [];
        for ($i = 0; $i < $numGames; $i++) {
            $team1 = array_shift($teams);
            $team2 = array_shift($teams);
            $scores = $this->scoreService->generateScore();

            $score1 = $scores[0];
            $score2 = $scores[1];

            $game = new Game($team1, $team2, $score1, $score2);
            $games[] = $game;

            $winner = $game->getWinner();
            $teams[] = $winner;
        }
        return $games;
    }
}