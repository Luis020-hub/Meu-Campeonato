<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Championship;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition()
    {
        return [
            'championship_id' => Championship::factory(),
            'host_id' => Team::factory(),
            'guest_id' => Team::factory(),
            'host_goals' => $this->faker->numberBetween(0, 5),
            'guest_goals' => $this->faker->numberBetween(0, 5),
            'penalty_host_goals' => null,
            'penalty_guest_goals' => null,
            'winner' => 'host',
            'loser' => 'guest',
            'round' => $this->faker->randomElement(['Quarterfinals', 'Semifinals', 'Final', 'ThirdPlace']),
        ];
    }
}