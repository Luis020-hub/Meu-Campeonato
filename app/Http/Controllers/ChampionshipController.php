<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChampionshipService;
use App\Services\ValidationService;
use App\Repositories\TeamRepository;
use App\Services\ScoreService;
use App\Models\Championship;

class ChampionshipController extends Controller
{
    private ChampionshipService $championshipService;
    private ValidationService $validationService;

    public function __construct()
    {
        $teamRepository = new TeamRepository();
        $scoreService = new ScoreService();
        $this->championshipService = new ChampionshipService($teamRepository, $scoreService);
        $this->validationService = new ValidationService();
    }

    public function index()
    {
        $championships = Championship::all();
        if ($championships->isEmpty()) {
            return view('championship.index')->with('message', 'There is no championship simulated');
        }
        return view('championship.index', ['championships' => $championships]);
    }

    public function simulate(Request $request)
    {
        $request->validate([
            'teams' => 'required|array|size:8',
            'teams.*' => 'required|string'
        ]);

        $teams = $request->input('teams');

        $validationResult = $this->validationService->validateTeams($teams);
        if (!$validationResult['isValid']) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validationResult['message']], 422);
            }
            return redirect('/')->withErrors($validationResult['message']);
        }

        $result = $this->championshipService->simulateChampionship($teams);

        $championship = Championship::latest()->first();

        session(['last_simulation' => $result, 'last_teams' => $teams]);

        if ($request->expectsJson()) {
            return response()->json([
                'championship_id' => $championship->id,
                'result' => $result
            ]);
        }

        return redirect()->route('championship.show', ['id' => $championship->id]);
    }

    public function historic(Request $request)
    {
        $championships = Championship::with('games')->get()->map(function ($championship) {
            $games = $championship->games;

            $finalGame = $games->where('round', 'Final')->first();
            $thirdPlaceGame = $games->where('round', 'ThirdPlace')->first();

            $ranking = [
                '1st' => $finalGame ? $finalGame->winner : 'N/A',
                '2nd' => $finalGame ? $finalGame->loser : 'N/A',
                '3rd' => $thirdPlaceGame ? $thirdPlaceGame->winner : 'N/A'
            ];

            return (object) [
                'id' => $championship->id,
                'ranking' => $ranking,
                'games' => $games
            ];
        });

        if ($championships->isEmpty()) {
            $message = 'No championships have been simulated yet';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 200);
            }
            return view('championship.historic', compact('message', 'championships'));  // Agora passa 'championships' mesmo que vazio
        }

        if ($request->expectsJson()) {
            return response()->json($championships);
        }

        return view('championship.historic', ['championships' => $championships]);
    }

    public function show(Request $request, $id)
    {
        $championship = Championship::with('games')->find($id);
        if (!$championship) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'This championship does not exist'], 404);
            }
            return redirect()->back()->withErrors('This championship does not exist');
        }

        $games = $championship->games->map(function ($game) {
            return (object) [
                'id' => $game->id,
                'championship_id' => $game->championship_id,
                'round' => $game->round,
                'host_id' => $game->host_id,
                'host_goals' => $game->host_goals,
                'guest_id' => $game->guest_id,
                'guest_goals' => $game->guest_goals,
                'penalty_host_goals' => $game->penalty_host_goals,
                'penalty_guest_goals' => $game->penalty_guest_goals,
                'loser' => $game->loser,
                'winner' => $game->winner,
            ];
        });

        $finalGame = $games->where('round', 'Final')->first();
        $thirdPlaceGame = $games->where('round', 'ThirdPlace')->first();

        $ranking = [
            '1st' => $finalGame ? $finalGame->winner : 'N/A',
            '2nd' => $finalGame ? $finalGame->loser : 'N/A',
            '3rd' => $thirdPlaceGame ? $thirdPlaceGame->winner : 'N/A'
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'championship' => (object) [
                    'id' => $championship->id,
                    'ranking' => $ranking,
                    'games' => $games
                ]
            ]);
        }

        return view('championship.show', [
            'championship' => $championship,
            'rounds' => $games,
            'ranking' => $ranking
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $championship = Championship::find($id);
        if (!$championship) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Championship does not exist'], 404);
            }
            return redirect()->back()->withErrors('Championship does not exist');
        }

        $championship->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => 'Championship deleted successfully']);
        }

        return redirect()->back()->with('success', 'Championship deleted successfully');
    }
}
