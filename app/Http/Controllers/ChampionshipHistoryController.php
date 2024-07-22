<?php

namespace App\Http\Controllers;

use App\Models\Championship;
use Illuminate\Http\Request;

class ChampionshipHistoryController extends Controller
{
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
            return view('championship.historic', compact('message', 'championships'));
        }

        if ($request->expectsJson()) {
            return response()->json($championships);
        }

        return view('championship.historic', ['championships' => $championships]);
    }
}