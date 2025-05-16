<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->foreignId('author_id')->references('id')->on('users');
            $table->foreignId('post_id')->references('id')->on('posts');
            $table->timestamps();

            // comment N ---- 1 post
            // comment N ---- 1 user
            // post 1 --- N comment N --- 1 user
            // 1 user ---- comment N

            // X 1 --- 1 Y
            // post 1 --- 1 preview
            // person 1 --- 1 passport
            // profile (id) (1..3) --- (id) profile_settings (4...7) ---- (id) profile_auth (8...14)

            // X 1 -- N pivot(x_id, y_id) N -- 1 Y
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
