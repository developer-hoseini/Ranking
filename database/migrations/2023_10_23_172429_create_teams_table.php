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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->longText('about')->nullable();
            $table->unsignedInteger('likes')->nullable();
            $table->foreignId('game_id')->nullable()->constrained();
            $table->foreignId('status_id')->nullable()->constrained();
            $table->foreignId('capitan_user_id')->nullable()->constrained('users', 'id');
            $table->foreignId('state_id')->nullable()->constrained();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users', 'id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
