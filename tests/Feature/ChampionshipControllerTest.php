<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Championship;
use App\Models\Game;
use App\Models\Team;

class ChampionshipControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Enter Teams');
    }

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

    public function testHistoric()
    {
        Championship::factory()->has(Game::factory()->count(3))->create();

        $response = $this->get('/historic');
        $response->assertStatus(200);
    }

    public function testShow()
    {
        $championship = Championship::factory()->has(Game::factory()->count(3))->create();

        $response = $this->get("/historic/{$championship->id}");
        $response->assertStatus(200);
    }
}