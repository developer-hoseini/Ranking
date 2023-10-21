<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invite_id')->constrained();
            $table->foreignId('inviter_game_result_status_id')->nullable()->constrained('statuses', 'id');
            $table->foreignId('invited_game_result_status_id')->nullable()->constrained('statuses', 'id');
            $table->foreignId('club_id')->constrained()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_results');
    }
};
