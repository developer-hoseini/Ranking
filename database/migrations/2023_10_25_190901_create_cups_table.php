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
        Schema::create('cups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 70);
            $table->smallInteger('capacity');
            $table->unsignedInteger('register_cost_coin')->nullable();
            $table->text('description')->nullable();

            $table->foreignId('created_by_user_id')->nullable()->constrained('users', 'id');

            $table->foreignId('state_id')->nullable()->constrained();
            $table->foreignId('game_id')->nullable()->constrained();
            $table->foreignId('status_id')->nullable()->constrained();

            $table->timestamp('end_register_at')->nullable();
            $table->timestamp('start_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cups');
    }
};
