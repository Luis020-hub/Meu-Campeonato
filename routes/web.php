<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChampionshipController;

Route::get('/', [ChampionshipController::class, 'index']);
Route::post('/simulate', [ChampionshipController::class, 'simulate']);
Route::get('/historic', [ChampionshipController::class, 'historic']);
Route::get('/historic/{id}', [ChampionshipController::class, 'show']);