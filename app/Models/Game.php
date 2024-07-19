<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'championship_id',
        'host',
        'guest',
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
}