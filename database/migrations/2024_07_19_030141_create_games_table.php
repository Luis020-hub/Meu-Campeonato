<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('championship_id')->constrained()->onDelete('cascade');
            $table->string('host');
            $table->string('guest');
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

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};