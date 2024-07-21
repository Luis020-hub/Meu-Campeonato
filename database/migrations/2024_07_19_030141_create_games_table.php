<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('championship_id')->constrained()->onDelete('cascade');
            $table->foreignId('host_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('guest_id')->constrained('teams')->onDelete('cascade');
            $table->integer('host_goals');
            $table->integer('guest_goals');
            $table->integer('penalty_host_goals')->nullable();
            $table->integer('penalty_guest_goals')->nullable();
            $table->string('winner');
            $table->string('loser');
            $table->string('round');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('games');
    }
}