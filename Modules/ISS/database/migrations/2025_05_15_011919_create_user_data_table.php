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
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            //$table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('user_roles');
            //$table->string('user_iss_login', 50);
            //$table->string('user_iss_password', 50);
            $table->string('user_iss_avatar_path')->nullable();

            $table->string('organization')->nullable();
            $table->string('name', 50)->nullable();
            $table->string('second_name', 50)->nullable()->comment('Father name');
            $table->string('last_name', 50)->nullable();
            $table->string('email', 50)->nullable();

            $table->string('web_token')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['role_id']);
            //$table->unique(['user_iss_login', 'user_iss_password']);
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_data');
    }
};
