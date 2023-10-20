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
        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inviter_user_id')->constrained('users', 'id');
            $table->foreignId('invited_user_id')->constrained('users', 'id');
            $table->foreignId('game_id')->constrained();
            $table->foreignId('game_type_id')->nullable()->constrained();
            $table->foreignId('club_id')->nullable()->constrained();
            $table->foreignId('game_status_id')->nullable()->constrained('statuses', 'id');
            $table->foreignId('confirm_status_id')->nullable()->constrained('statuses', 'id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
