<?php

namespace App\Http\Controllers;

use App\Models\Championship;
use Illuminate\Http\Request;

class ChampionshipManagementController extends Controller
{
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