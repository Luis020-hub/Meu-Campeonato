<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChampionshipService;
use App\Repositories\TeamRepository;
use App\Services\ScoreService;

class ChampionshipController extends Controller
{
    private ChampionshipService $championshipService;

    public function __construct()
    {
        $teamRepository = new TeamRepository();
        $scoreService = new ScoreService();
        $this->championshipService = new ChampionshipService($teamRepository, $scoreService);
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
        $rounds = $this->championshipService->simulateChampionship($teams);

        return view('championship.results', compact('rounds'));
    }
}