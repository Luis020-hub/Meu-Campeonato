<?php

namespace App\Services;

use App\Domain\Team;
use App\Domain\Game;
use App\Domain\PenaltyShootout;
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
        $winnersQuarterfinals = array_map(fn ($game) => $this->teamRepository->findByName($game['winner']['name']), $quarterfinals);

        $semifinals = $this->simulateRound($winnersQuarterfinals, 2);
        $winnersSemifinals = array_map(fn ($game) => $this->teamRepository->findByName($game['winner']['name']), $semifinals);
        $losersSemifinals = array_map(fn ($game) => $this->teamRepository->findByName($game['loser']['name']), $semifinals);

        $final = $this->simulateRound($winnersSemifinals, 1);

        $thirdPlace = $this->simulateRound($losersSemifinals, 1);

        $rounds = [
            'Quarterfinals' => $quarterfinals,
            'Semifinals' => $semifinals,
            'Final' => $final
        ];

        $ranking = [
            '1st' => $final[0]['winner'],
            '2nd' => $final[0]['loser'],
            '3rd' => $thirdPlace[0]['winner']
        ];

        return ['rounds' => $rounds, 'ranking' => $ranking];
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

            if ($score1 == $score2) {
                $penaltyShootout = new PenaltyShootout();
                list($penaltyScore1, $penaltyScore2) = $penaltyShootout->resolve($team1, $team2);
                $game->setPenalties($penaltyScore1, $penaltyScore2);
            }

            $games[] = $game->toArray();

            $teams[] = $game->getWinner();
        }
        return $games;
    }
}