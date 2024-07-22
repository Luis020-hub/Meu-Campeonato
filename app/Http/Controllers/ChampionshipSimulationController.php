<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChampionshipService;
use App\Services\ValidationService;
use App\Models\Championship;

class ChampionshipSimulationController extends Controller
{
    private ChampionshipService $championshipService;
    private ValidationService $validationService;

    public function __construct(ChampionshipService $championshipService, ValidationService $validationService)
    {
        $this->championshipService = $championshipService;
        $this->validationService = $validationService;
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
}