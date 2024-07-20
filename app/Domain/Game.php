<?php

namespace App\Domain;

use App\Models\Team;

class Game
{
    private Team $host;
    private Team $guest;
    private int $hostGoals;
    private int $guestGoals;
    private ?int $penaltyHostGoals = null;
    private ?int $penaltyGuestGoals = null;
    private Team $winner;
    private Team $loser;

    public function __construct(Team $host, Team $guest, int $hostGoals, int $guestGoals)
    {
        $this->host = $host;
        $this->guest = $guest;
        $this->hostGoals = $hostGoals;
        $this->guestGoals = $guestGoals;

        if ($hostGoals > $guestGoals) {
            $this->winner = $host;
            $this->loser = $guest;
        } elseif ($guestGoals > $hostGoals) {
            $this->winner = $guest;
            $this->loser = $host;
        }
    }

    public function setPenalties(int $penaltyHostGoals, int $penaltyGuestGoals): void
    {
        $this->penaltyHostGoals = $penaltyHostGoals;
        $this->penaltyGuestGoals = $penaltyGuestGoals;

        if ($penaltyHostGoals > $penaltyGuestGoals) {
            $this->winner = $this->host;
            $this->loser = $this->guest;
        } else {
            $this->winner = $this->guest;
            $this->loser = $this->host;
        }
    }

    public function getHost(): Team
    {
        return $this->host;
    }

    public function getGuest(): Team
    {
        return $this->guest;
    }

    public function getHostGoals(): int
    {
        return $this->hostGoals;
    }

    public function getGuestGoals(): int
    {
        return $this->guestGoals;
    }

    public function getPenaltyHostGoals(): ?int
    {
        return $this->penaltyHostGoals;
    }

    public function getPenaltyGuestGoals(): ?int
    {
        return $this->penaltyGuestGoals;
    }

    public function getWinner(): Team
    {
        return $this->winner;
    }

    public function getLoser(): Team
    {
        return $this->loser;
    }

    public function toArray(): array
    {
        return [
            'host' => [
                'name' => $this->host->name,
                'goals' => $this->hostGoals
            ],
            'guest' => [
                'name' => $this->guest->name,
                'goals' => $this->guestGoals
            ],
            'penalties' => $this->penaltyHostGoals !== null && $this->penaltyGuestGoals !== null ? [
                'host' => $this->penaltyHostGoals,
                'guest' => $this->penaltyGuestGoals
            ] : null,
            'winner' => [
                'name' => $this->winner->name
            ],
            'loser' => [
                'name' => $this->loser->name
            ]
        ];
    }
}