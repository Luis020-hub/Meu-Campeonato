<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChampionshipController;

Route::get('/', [ChampionshipController::class, 'index']);
Route::get('/getToken', function () {return response()->json(['csrf_token' => csrf_token()]);});
Route::post('/simulate', [ChampionshipController::class, 'simulate']);
Route::get('/historic', [ChampionshipController::class, 'historic']);
Route::get('/historic/{id}', [ChampionshipController::class, 'show'])->name('championship.show');
Route::delete('/historic/{id}', [ChampionshipController::class, 'destroy'])->name('championship.destroy');