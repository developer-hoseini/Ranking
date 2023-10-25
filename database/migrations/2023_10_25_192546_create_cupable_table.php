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
        Schema::create('cupables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cup_id')->constrained();
            $table->morphs('cupable');
            $table->unsignedSmallInteger('step')->nullable();

            $table->unique(['cup_id', 'cupable_id', 'cupable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupables');
    }
};
