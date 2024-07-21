<?php

namespace Tests\Unit;

use App\Services\ChampionshipService;
use App\Repositories\TeamRepository;
use App\Services\ScoreService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Team;
use App\Models\Game as GameModel;
use App\Models\Championship;
use App\Domain\Game;
use App\Domain\PenaltyShootout;

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
            $team = Team::factory()->create(['name' => $teamName]);
            return $team;
        });

        $this->teamRepository->method('findByName')->willReturnCallback(function ($teamName) {
            return Team::where('name', $teamName)->first();
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

    public function testSimulateRound()
    {
        $championship = Championship::factory()->create();

        $championshipId = $championship->id;
        $teams = [
            Team::factory()->create(['name' => 'Team A']),
            Team::factory()->create(['name' => 'Team B']),
            Team::factory()->create(['name' => 'Team C']),
            Team::factory()->create(['name' => 'Team D'])
        ];

        $this->scoreService->method('generateScore')->willReturn([1, 0]);

        $this->assertDatabaseCount('games', 0);

        $reflection = new \ReflectionClass($this->championshipService);
        $method = $reflection->getMethod('simulateRound');
        $method->setAccessible(true);

        $results = $method->invokeArgs($this->championshipService, [$championshipId, &$teams, 2, 'Quarterfinals']);

        $this->assertIsArray($results);
        $this->assertCount(2, $results);

        foreach ($results as $game) {
            $this->assertArrayHasKey('host', $game);
            $this->assertArrayHasKey('guest', $game);
            $this->assertArrayHasKey('goals', $game['host']);
            $this->assertArrayHasKey('goals', $game['guest']);
            $this->assertArrayHasKey('winner', $game);
            $this->assertArrayHasKey('loser', $game);
            $this->assertEquals(1, $game['host']['goals']);
            $this->assertEquals(0, $game['guest']['goals']);

            $this->assertDatabaseHas('games', [
                'championship_id' => $championshipId,
                'host_id' => Team::where('name', $game['host']['name'])->first()->id,
                'guest_id' => Team::where('name', $game['guest']['name'])->first()->id,
                'host_goals' => $game['host']['goals'],
                'guest_goals' => $game['guest']['goals'],
                'winner' => $game['winner']['name'],
                'loser' => $game['loser']['name'],
                'round' => 'Quarterfinals',
            ]);
        }

        $this->assertDatabaseCount('games', 2);
    }
}