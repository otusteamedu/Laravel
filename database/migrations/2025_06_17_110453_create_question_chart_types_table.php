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
        Schema::create('question_chart_types', function (Blueprint $table) {
            $table->integer('id')->unique()->comment('Идентификатор типа графика вопроса.');
            $table->string('name')->comment('Наименование типа графика вопроса.');
            $table->string('alias')->comment('Псевдоним типа графика вопроса.');

            $table->index('id');
            $table->comment('Таблица типов графиков вопроса.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_chart_types');
    }
};
