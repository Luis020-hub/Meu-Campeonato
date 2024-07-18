<?php

namespace App\Domain;

class PenaltyShootout
{
    public function resolve(): array
    {
        $penaltyScore1 = $this->takePenalties();
        $penaltyScore2 = $this->takePenalties();

        while ($penaltyScore1 == $penaltyScore2) {
            $penaltyScore1 += rand(0, 1);
            $penaltyScore2 += rand(0, 1);
        }

        return [$penaltyScore1, $penaltyScore2];
    }

    private function takePenalties(): int
    {
        return rand(3, 5);
    }
}