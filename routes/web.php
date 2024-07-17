<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChampionshipController;

Route::get('/', [ChampionshipController::class, 'index']);
Route::post('/simulate', [ChampionshipController::class, 'simulate']);