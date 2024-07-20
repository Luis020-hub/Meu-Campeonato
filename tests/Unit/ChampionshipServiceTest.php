<?php

namespace Tests\Unit;

use App\Services\ChampionshipService;
use App\Repositories\TeamRepository;
use App\Services\ScoreService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Team;

class ChampionshipServiceTest extends TestCase
{
    use RefreshDatabase;

    private $teamRepository;
    private $scoreService;
    private $championshipService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teamRepository = $this->createMock(TeamRepository::class);
        $this->scoreService = $this->createMock(ScoreService::class);

        $this->championshipService = new ChampionshipService($this->teamRepository, $this->scoreService);
    }

    public function testSimulateChampionship()
    {
        $teamNames = ['Team A', 'Team B', 'Team C', 'Team D', 'Team E', 'Team F', 'Team G', 'Team H'];

        $this->teamRepository->method('findOrCreateByName')->willReturnCallback(function ($teamName) {
            $team = new Team();
            $team->name = $teamName;
            return $team;
        });

        $this->teamRepository->method('findByName')->willReturnCallback(function ($teamName) {
            $team = new Team();
            $team->name = $teamName;
            return $team;
        });

        $this->scoreService->method('generateScore')->willReturn([1, 0]);

        $results = $this->championshipService->simulateChampionship($teamNames);

        $this->assertIsArray($results);
        $this->assertArrayHasKey('rounds', $results);
        $this->assertArrayHasKey('ranking', $results);

        $this->assertCount(4, $results['rounds']['Quarterfinals']);
        $this->assertCount(2, $results['rounds']['Semifinals']);
        $this->assertCount(1, $results['rounds']['ThirdPlace']);
        $this->assertCount(1, $results['rounds']['Final']);

        $this->assertArrayHasKey('1st', $results['ranking']);
        $this->assertArrayHasKey('2nd', $results['ranking']);
        $this->assertArrayHasKey('3rd', $results['ranking']);
    }
}