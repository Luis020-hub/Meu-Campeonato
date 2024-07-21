<?php

namespace App\Services;

use App\Domain\Team;
use App\Domain\Game;
use App\Domain\PenaltyShootout;
use App\Repositories\TeamRepository;
use App\Services\ScoreService;
use App\Models\Championship;
use App\Models\Game as GameModel;

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
        $teams = [];
        foreach ($teamNames as $teamName) {
            $team = $this->teamRepository->findOrCreateByName($teamName);
            $teams[] = $team;
        }

        shuffle($teams);

        $championship = Championship::create();

        $quarterfinals = $this->simulateRound($championship->id, $teams, 4, 'Quarterfinals');
        $winnersQuarterfinals = array_map(fn ($game) => $this->teamRepository->findByName($game['winner']['name']), $quarterfinals);

        $semifinals = $this->simulateRound($championship->id, $winnersQuarterfinals, 2, 'Semifinals');
        $winnersSemifinals = array_map(fn ($game) => $this->teamRepository->findByName($game['winner']['name']), $semifinals);
        $losersSemifinals = array_map(fn ($game) => $this->teamRepository->findByName($game['loser']['name']), $semifinals);

        $final = $this->simulateRound($championship->id, $winnersSemifinals, 1, 'Final');
        $thirdPlace = $this->simulateRound($championship->id, $losersSemifinals, 1, 'ThirdPlace');

        $rounds = [
            'Quarterfinals' => $quarterfinals,
            'Semifinals' => $semifinals,
            'ThirdPlace' => $thirdPlace,
            'Final' => $final
        ];

        $ranking = [
            '1st' => $final[0]['winner'],
            '2nd' => $final[0]['loser'],
            '3rd' => $thirdPlace[0]['winner']
        ];

        return ['rounds' => $rounds, 'ranking' => $ranking];
    }

    private function simulateRound(int $championshipId, array &$teams, int $numGames, string $roundName): array
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
                list($penaltyScore1, $penaltyScore2) = $penaltyShootout->resolve();
                $game->setPenalties($penaltyScore1, $penaltyScore2);
            }

            $games[] = $game->toArray();

            GameModel::create([
                'championship_id' => $championshipId,
                'host_id' => $game->getHost()->id,
                'guest_id' => $game->getGuest()->id,
                'host_goals' => $game->getHostGoals(),
                'guest_goals' => $game->getGuestGoals(),
                'penalty_host_goals' => $game->getPenaltyHostGoals(),
                'penalty_guest_goals' => $game->getPenaltyGuestGoals(),
                'winner' => $game->getWinner()->name,
                'loser' => $game->getLoser()->name,
                'round' => $roundName,
            ]);

            $teams[] = $game->getWinner();
        }
        return $games;
    }
}