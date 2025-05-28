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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            // Например: "Соболев-Бот"
            $table->string('nickname');
            // Полное имя, например: "Александр Соболев"
            $table->string('name');
            // Позиция на поле: "GK", "DF", "MF", "FW"
            $table->string('position');
            // ID команды, к которой принадлежит игрок (связь с таблицей teams)
            $table->foreignId('team_id')->constrained();
            // Стоимость игрока в условных единицах
            $table->unsignedInteger('price')->default(0);
            // Фото или иконка
            $table->string('avatar_path')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
