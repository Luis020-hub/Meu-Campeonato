<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Team;
use App\Models\Championship;

class ChampionshipSimulationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSimulateValidation()
    {
        $response = $this->post('/simulate', ['teams' => ['Team 1', 'Team 2']]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['teams']);
    }

    public function testSimulateChampionship()
    {
        $teams = Team::factory()->count(8)->create();
        $teamNames = $teams->pluck('name')->toArray();

        $response = $this->post('/simulate', ['teams' => $teamNames]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('championships', [
            'id' => Championship::latest()->first()->id
        ]);
    }
}