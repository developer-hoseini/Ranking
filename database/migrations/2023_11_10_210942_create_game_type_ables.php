<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_type_ables', function (Blueprint $table) {
            $table->id();
            $table->morphs('game_type_able');
            $table->foreignId('game_type_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_type_ables');
    }
};
