<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChampionshipService;
use App\Services\ValidationService;
use App\Repositories\TeamRepository;
use App\Services\ScoreService;

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

        $rounds = $this->championshipService->simulateChampionship($teams);

        if ($request->expectsJson()) {
            return response()->json(['rounds' => $rounds]);
        }

        return view('championship.results', ['rounds' => $rounds]);
    }
}