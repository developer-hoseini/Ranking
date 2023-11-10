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
        Schema::create('coin_requests', function (Blueprint $table) {
            $table->id();

            $table->string('wallet_address')->nullable();
            $table->unsignedInteger('count');
            $table->dateTime('requested_at')->nullable();
            $table->enum('type', ['buy', 'sell'])->default('buy');

            $table->foreignId('status_id')->constrained();
            $table->foreignId('created_by_user_id')->constrained('users', 'id');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_requests');
    }
};
