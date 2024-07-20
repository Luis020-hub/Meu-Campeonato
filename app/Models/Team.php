<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'points', 'goals_scored', 'goals_conceded'];

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}