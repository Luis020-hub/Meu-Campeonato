<?php

namespace Database\Factories;

use App\Models\Championship;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChampionshipFactory extends Factory
{
    protected $model = Championship::class;

    public function definition()
    {
        return [
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}