<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChampionshipService;
use App\Repositories\TeamRepository;

class ChampionshipController extends Controller
{
    private ChampionshipService $championshipService;

    public function __construct()
    {
        $this->championshipService = new ChampionshipService(new TeamRepository());
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