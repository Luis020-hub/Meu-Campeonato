<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Repositories\TeamRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected TeamRepository $teamRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->teamRepository = new TeamRepository();
    }

    public function testSave()
    {
        $team = new Team(['name' => 'Team A']);
        $this->teamRepository->save($team);

        $this->assertDatabaseHas('teams', ['name' => 'Team A']);
    }

    public function testFindAll()
    {
        Team::factory()->create(['name' => 'Team A']);
        Team::factory()->create(['name' => 'Team B']);

        $teams = $this->teamRepository->findAll();

        $this->assertCount(2, $teams);
        $this->assertEquals('Team A', $teams[0]['name']);
        $this->assertEquals('Team B', $teams[1]['name']);
    }

    public function testFindByName()
    {
        $team = Team::factory()->create(['name' => 'Team A']);

        $foundTeam = $this->teamRepository->findByName('Team A');

        $this->assertNotNull($foundTeam);
        $this->assertEquals($team->id, $foundTeam->id);
    }

    public function testFindOrCreateByName()
    {
        $team = $this->teamRepository->findOrCreateByName('Team A');

        $this->assertNotNull($team);
        $this->assertDatabaseHas('teams', ['name' => 'Team A']);

        $existingTeam = $this->teamRepository->findOrCreateByName('Team A');

        $this->assertEquals($team->id, $existingTeam->id);
    }

    public function testClear()
    {
        Team::factory()->create(['name' => 'Team A']);
        $this->teamRepository->clear();

        $this->assertDatabaseCount('teams', 0);
    }
}