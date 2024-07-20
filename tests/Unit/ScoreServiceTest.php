<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ScoreService;
use Illuminate\Support\Facades\Log;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;

class ScoreServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $app = new Container();
        $app->singleton('log', function () {
            return Log::spy();
        });
        Facade::setFacadeApplication($app);
        Log::shouldReceive('info')->andReturn(true);
        Log::shouldReceive('error')->andReturn(true);
    }

    public function testGenerateScore()
    {
        $scoreService = new ScoreService();

        $score = $scoreService->generateScore();

        $this->assertIsInt($score[0]);
        $this->assertIsInt($score[1]);

        $this->assertGreaterThanOrEqual(0, $score[0]);
        $this->assertGreaterThanOrEqual(0, $score[1]);
        $this->assertLessThanOrEqual(7, $score[0]);
        $this->assertLessThanOrEqual(7, $score[1]);
    }
}
