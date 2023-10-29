<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('game_id')->constrained('games', 'id');
            $table->foreignId('country_id')->constrained('countries', 'id');
            $table->boolean('is_join');
            $table->mediumInteger('in_club')->default(0);
            $table->mediumInteger('with_image')->default(0);
            $table->mediumInteger('team_played')->default(0);
            $table->integer('score')->default(100);
            $table->integer('win')->default(0);
            $table->integer('lose')->default(0);
            $table->integer('fault')->default(0);
            $table->integer('warning')->default(0);
            $table->datetime('join_dt')->useCurrent();
            $table->integer('coin')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_scores');
    }
};
