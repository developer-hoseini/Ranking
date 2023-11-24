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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();

            $table->morphs('achievementable');

            $table->enum('type', ['score', 'coin', 'join']);
            $table->integer('count');

            $table->unsignedBigInteger('occurred_model_id')->nullable()->index();
            $table->string('occurred_model_type')->nullable()->index();

            $table->foreignId('status_id')->nullable()->constrained();
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
        Schema::dropIfExists('achievements');
    }
};
