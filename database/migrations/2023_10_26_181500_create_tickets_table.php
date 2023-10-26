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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('content')->nullable();
            $table->unsignedBigInteger('ticket_parent_id')->nullable();
            $table->foreignId('ticket_category_id')->nullable()->constrained('ticket_categories', 'id');
            $table->foreignId('status_id')->nullable()->constrained();
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
        Schema::dropIfExists('tickets');
    }
};
