<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChampionshipController;
use App\Http\Controllers\ChampionshipSimulationController;
use App\Http\Controllers\ChampionshipHistoryController;
use App\Http\Controllers\ChampionshipManagementController;

Route::get('/', [ChampionshipController::class, 'index']);
Route::get('/getToken', function () {return response()->json(['csrf_token' => csrf_token()]);});
Route::post('/simulate', [ChampionshipSimulationController::class, 'simulate']);
Route::get('/historic', [ChampionshipHistoryController::class, 'historic']);
Route::get('/historic/{id}', [ChampionshipController::class, 'show'])->name('championship.show');
Route::delete('/historic/{id}', [ChampionshipManagementController::class, 'destroy'])->name('championship.destroy');