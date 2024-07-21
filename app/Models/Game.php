<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'championship_id',
        'host_id',
        'guest_id',
        'host_goals',
        'guest_goals',
        'penalty_host_goals',
        'penalty_guest_goals',
        'winner',
        'loser',
        'round'
    ];

    public function championship()
    {
        return $this->belongsTo(Championship::class);
    }

    public function host()
    {
        return $this->belongsTo(Team::class, 'host_id');
    }

    public function guest()
    {
        return $this->belongsTo(Team::class, 'guest_id');
    }
}