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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->unsignedInteger('coin')->default(0);
            $table->unsignedInteger('capacity')->default(2);
            $table->text('description')->nullable();

            $table->foreignId('game_id')->constrained();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('status_id')->constrained();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users', 'id');

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
        Schema::dropIfExists('competitions');
    }
};
