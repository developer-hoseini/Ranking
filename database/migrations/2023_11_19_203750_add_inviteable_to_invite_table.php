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
        Schema::table('invites', function (Blueprint $table) {
            $table->unsignedBigInteger('inviteable_id')->nullable();
            $table->string('inviteable_type')->nullable();

            $table->index(['inviteable_id', 'inviteable_type']);

            $table->unsignedBigInteger('game_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->dropIndex(['inviteable_id', 'inviteable_type']);
            $table->dropColumn('inviteable_type');
            $table->dropColumn('inviteable_id');

        });
    }
};
