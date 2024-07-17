<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ChampionshipRoutesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_access_the_home_route()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Insira os Times');
    }

    #[Test]
    public function it_can_simulate_the_championship()
    {
        $teams = [
            'Team 1', 'Team 2', 'Team 3', 'Team 4',
            'Team 5', 'Team 6', 'Team 7', 'Team 8'
        ];

        $response = $this->post('/simulate', ['teams' => $teams]);

        $response->assertStatus(200);
        $response->assertSee('Resultados do Campeonato');
    }
}