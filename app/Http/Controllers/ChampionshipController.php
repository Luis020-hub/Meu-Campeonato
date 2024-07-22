<?php

namespace App\Http\Controllers;

use App\Models\Championship;
use Illuminate\Http\Request;

class ChampionshipController extends Controller
{
    public function index()
    {
        $championships = Championship::all();
        if ($championships->isEmpty()) {
            return view('championship.index')->with('message', 'There is no championship simulated');
        }
        return view('championship.index', ['championships' => $championships]);
    }

    public function show(Request $request, $id)
    {
        try {
            $championship = Championship::with(['games.host', 'games.guest'])->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $message = 'This championship does not exist';

            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 404);
            }

            return redirect()->back()->withErrors($message);
        }

        $games = $championship->games->map(function ($game) {
            return (object) [
                'id' => $game->id,
                'championship_id' => $game->championship_id,
                'round' => $game->round,
                'host' => $game->host->name,
                'host_goals' => $game->host_goals,
                'guest' => $game->guest->name,
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
            'rounds' => $games->groupBy('round'),
            'ranking' => $ranking
        ]);
    }
}