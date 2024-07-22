<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Championship;

class ChampionshipControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Enter Teams');
    }

    public function testShowChampionship()
    {
        $championship = Championship::factory()->create();

        $response = $this->get('/historic/' . $championship->id);
        $response->assertStatus(200);
        $response->assertViewHas('championship');
    }

    public function testShowNonExistentChampionship()
    {
        $response = $this->get('/historic/999');
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}