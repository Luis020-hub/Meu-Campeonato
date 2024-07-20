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
        return view('championship.index');
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
            return response()->json($result);
        }

        return redirect()->route('championship.show', ['id' => $championship->id]);
    }

    public function historic()
    {
        $championships = Championship::with('games')->get();

        $championships->each(function ($championship) {
            $games = $championship->games->groupBy('round');

            $championship->ranking = [
                '1st' => $games->has('Final') ? $games['Final']->first()->winner : 'N/A',
                '2nd' => $games->has('Final') ? $games['Final']->first()->loser : 'N/A',
                '3rd' => $games->has('ThirdPlace') ? $games['ThirdPlace']->first()->winner : 'N/A'
            ];
        });

        return view('championship.historic', ['championships' => $championships]);
    }

    public function show($id)
    {
        $championship = Championship::with('games')->findOrFail($id);

        $games = $championship->games->groupBy('round');
        $ranking = [
            '1st' => $games->has('Final') ? $games['Final']->first()->winner : 'N/A',
            '2nd' => $games->has('Final') ? $games['Final']->first()->loser : 'N/A',
            '3rd' => $games->has('ThirdPlace') ? $games['ThirdPlace']->first()->winner : 'N/A'
        ];

        return view('championship.show', ['championship' => $championship, 'rounds' => $games, 'ranking' => $ranking]);
    }
}