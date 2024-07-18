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

    public function simulateChampionship(array $teamNames): array
    {
        $this->teamRepository->clear();

        $teams = [];
        foreach ($teamNames as $teamName) {
            $team = new Team($teamName);
            $this->teamRepository->save($team);
            $teams[] = $team;
        }

        shuffle($teams);

        $quarterfinals = $this->simulateRound($teams, 4);
        $winners = array_map(fn ($game) => $this->teamRepository->findByName($game['winner']['name']), $quarterfinals);

        $semifinals = $this->simulateRound($winners, 2);
        $winners = array_map(fn ($game) => $this->teamRepository->findByName($game['winner']['name']), $semifinals);

        $final = $this->simulateRound($winners, 1);

        $rounds = [
            'quarterfinals' => $quarterfinals,
            'semifinals' => $semifinals,
            'final' => $final
        ];

        return $rounds;
    }

    private function simulateRound(array &$teams, int $numGames): array
    {
        $games = [];
        for ($i = 0; $i < $numGames; $i++) {
            if (count($teams) < 2) {
                break;
            }
            $team1 = array_shift($teams);
            $team2 = array_shift($teams);

            $scores = $this->scoreService->generateScore();
            $score1 = $scores[0];
            $score2 = $scores[1];

            $game = new Game($team1, $team2, $score1, $score2);

            $gameData = [
                'game' => $game->toArray(),
                'winner' => $game->getWinner()->toArray(),
                'loser' => $game->getLoser()->toArray()
            ];

            if ($score1 === $score2) {
                $gameData['penalties'] = $game->toArray()['penalties'];
            }

            $games[] = $gameData;

            $teams[] = $game->getWinner();
        }
        return $games;
    }
}