<?php

namespace Tests\Feature;

use App\Models\Championship;
use App\Models\Game;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChampionshipRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function testHomeRoute()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Enter Teams');
    }

    public function testCsrfTokenRoute()
    {
        $response = $this->get('/getToken');
        $response->assertStatus(200);
        $response->assertJsonStructure(['csrf_token']);
    }

    public function testSimulateChampionship()
    {
        $teams = [
            'Team1', 'Team2', 'Team3', 'Team4',
            'Team5', 'Team6', 'Team7', 'Team8'
        ];

        $response = $this->post('/simulate', ['teams' => $teams]);
        $response->assertStatus(302);

        $championship = Championship::latest()->first();
        $this->assertNotNull($championship);
        $response->assertRedirect(route('championship.show', ['id' => $championship->id]));
    }

    public function testHistoricRoute()
    {
        $response = $this->get('/historic');
        $response->assertStatus(200);
        $response->assertViewIs('championship.historic');
    }

    public function testShowRoute()
    {
        $championship = Championship::factory()->create();

        $response = $this->get(route('championship.show', ['id' => $championship->id]));
        $response->assertStatus(200);
        $response->assertViewIs('championship.show');
    }

    public function testDestroyRoute()
    {
        $championship = Championship::factory()
            ->has(Game::factory()->count(3))
            ->create();

        $response = $this->delete(route('championship.destroy', ['id' => $championship->id]));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Championship deleted successfully');

        $this->assertDatabaseMissing('championships', [
            'id' => $championship->id
        ]);
    }
}