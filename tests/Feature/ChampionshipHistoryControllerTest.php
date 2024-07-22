<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Championship;
use App\Models\Game;

class ChampionshipHistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testHistoric()
    {
        Championship::factory()->has(Game::factory()->count(3))->create();

        $response = $this->get('/historic');
        $response->assertStatus(200);
        $response->assertViewHas('championships');
    }

    public function testHistoricNoChampionships()
    {
        $response = $this->get('/historic');
        $response->assertStatus(200);

        $response->assertSee('<h1>Championship History</h1>', false);
        $response->assertSee('<a href="/">Back</a>', false);

        $response->assertViewHas('championships', function ($championships) {
            return $championships->isEmpty();
        });
    }
}