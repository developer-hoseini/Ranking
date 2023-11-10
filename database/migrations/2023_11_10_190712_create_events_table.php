<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('invite_id')->nullable()->constrained('invites', 'id');
            $table->string('type')->nullable();
            $table->string('reason');
            $table->integer('seen')->default(0);
            $table->foreignId('team_id')->nullable()->constrained('teams', 'id');
            $table->integer('team_user_id')->nullable()->constrained('team_user', 'id');
            $table->integer('competition_id')->nullable()->constrained('competitions', 'id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
