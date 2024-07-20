<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Domain\PenaltyShootout;

class PenaltyShootoutTest extends TestCase
{
    public function testResolvePenalties()
    {
        $penaltyShootout = new PenaltyShootout();

        list($penaltyScore1, $penaltyScore2) = $penaltyShootout->resolve();

        $this->assertGreaterThanOrEqual(3, $penaltyScore1);
        $this->assertGreaterThanOrEqual(3, $penaltyScore2);

        $this->assertNotEquals($penaltyScore1, $penaltyScore2);
    }
}