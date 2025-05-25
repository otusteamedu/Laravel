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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            // Например: "Хрюшки", "Полицаи"
            $table->string('nickname');
            // Полное название команды (например: "Спартак Москва")
            $table->string('name');
            // Можно добавить эмблему
            $table->string('logo_path')->nullable(); // путь к иконке команды
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
