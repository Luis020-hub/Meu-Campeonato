<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Championship;
use App\Models\Game;

class ChampionshipManagementControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testDestroy()
    {
        $championship = Championship::factory()
            ->has(Game::factory()->count(3))
            ->create();

        $response = $this->delete("/historic/{$championship->id}");

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Championship deleted successfully');

        $this->assertDatabaseMissing('championships', [
            'id' => $championship->id
        ]);
    }

    public function testDestroyNonExistentChampionship()
    {
        $response = $this->delete('/historic/999');
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}