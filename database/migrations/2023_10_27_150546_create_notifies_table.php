<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->text('message');
            $table->foreignId('country_id')->nullable()->constrained('countries', 'id');
            $table->foreignId('club_id')->nullable()->constrained('clubs', 'id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifies');
    }
};
