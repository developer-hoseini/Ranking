<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->string('avatar_name')->nullable()->unique();

            $table->string('fname', 50)->nullable();
            $table->string('lname', 50)->nullable();
            $table->string('bio', 100)->nullable();
            $table->foreignId('state_id')->nullable()->constrained('states', 'id');
            $table->date('birth_date')->nullable();
            $table->tinyInteger('gender')->default(1);
            $table->string('mobile', 30)->nullable();
            $table->string('code_melli', 50)->nullable();
            $table->string('en_fullname', 100)->nullable();
            $table->string('bank_account', 100)->nullable();
            $table->string('account_holder_name', 100)->nullable();
            $table->tinyInteger('show_mobile')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
