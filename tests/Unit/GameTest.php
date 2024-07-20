<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Domain\Game;
use App\Models\Team;

class GameTest extends TestCase
{
    public function testGameCreation()
    {
        $host = new Team(['name' => 'Team A']);
        $guest = new Team(['name' => 'Team B']);
        $game = new Game($host, $guest, 3, 2);

        $this->assertEquals($host, $game->getHost());
        $this->assertEquals($guest, $game->getGuest());
        $this->assertEquals(3, $game->getHostGoals());
        $this->assertEquals(2, $game->getGuestGoals());
        $this->assertEquals($host, $game->getWinner());
        $this->assertEquals($guest, $game->getLoser());
    }

    public function testGameDrawWithPenalties()
    {
        $host = new Team(['name' => 'Team A']);
        $guest = new Team(['name' => 'Team B']);
        $game = new Game($host, $guest, 1, 1);

        $game->setPenalties(4, 5);

        $this->assertEquals(4, $game->getPenaltyHostGoals());
        $this->assertEquals(5, $game->getPenaltyGuestGoals());
        $this->assertEquals($guest, $game->getWinner());
        $this->assertEquals($host, $game->getLoser());
    }

    public function testGameToArray()
    {
        $host = new Team(['name' => 'Team A']);
        $guest = new Team(['name' => 'Team B']);
        $game = new Game($host, $guest, 2, 2);

        $game->setPenalties(5, 4);

        $expected = [
            'host' => [
                'name' => 'Team A',
                'goals' => 2
            ],
            'guest' => [
                'name' => 'Team B',
                'goals' => 2
            ],
            'penalties' => [
                'host' => 5,
                'guest' => 4
            ],
            'winner' => [
                'name' => 'Team A'
            ],
            'loser' => [
                'name' => 'Team B'
            ]
        ];

        $this->assertEquals($expected, $game->toArray());
    }
}