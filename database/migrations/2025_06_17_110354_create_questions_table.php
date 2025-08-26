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
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->unique()->comment('Идентификатор вопроса.');
            $table->uuid('poll_id')->comment('Идентификатор опроса.');
            $table->tinyInteger('order_number')->comment('Порядколвый номер.');
            $table->boolean('required')->default(true)->comment('Признак обязательного ответа на вопрос.');
            $table->text('name')->comment('Наименование вопроса.');
            $table->text('name_for_chart')->nullable()->comment('Наименование вопроса для графиков.');
            $table->integer('type_id')->comment('Идентификатор типа вопроса.');
            $table->tinyInteger('min_answer_count')->nullable()->comment('Минимальное количество ответов (для вопроса с множественным выбором).');
            $table->tinyInteger('max_answer_count')->nullable()->comment('Максимальное количество ответов (для вопроса с множественным выбором).');
            $table->boolean('comment')->default(false)->comment('Признак отображения поля с комментарием.');
            $table->string('icon')->nullable()->comment('Иконка вопроса.');

            $table->index('id');
            $table->index('poll_id');
            $table->index('type_id');
            $table->foreign('poll_id')->references('id')->on('polls');
            $table->foreign('type_id')->references('id')->on('question_types');

            $table->comment('Таблица вопросов для опроса.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
