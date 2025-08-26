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
        Schema::create('question_answers', function (Blueprint $table) {
            $table->uuid('id')->unique()->comment('Идентификатор ответа.');
            $table->uuid('poll_id')->comment('Идентификатор опроса');
            $table->uuid('question_id')->comment('Идентификатор вопроса.');
            $table->integer('order_number')->comment('Порядколвый номер.');
            $table->text('name')->comment('Наименование ответа.');
            $table->text('name_for_chart')->nullable()->comment('Наименование ответа для графиков.');
            $table->boolean('self')->default(false)->comment('Признак отображения поля для ввода текста (например свой вариант ответа).');
            $table->boolean('selected')->default(false)->comment('Признак выбора ответа по умолчанию.');
            $table->string('icon')->nullable()->comment('Иконка ответа.');
            $table->jsonb('excluded_order_numbers')->nullable()->comment('Массив порядковых номеров опроса, для пропуска.');
            $table->text('end_text')->nullable()->comment('Финальный текст при выборе данного ответа');

            $table->index('poll_id');
            $table->index('question_id');
            $table->foreign('poll_id')->references('id')->on('polls');
            $table->foreign('question_id')->references('id')->on('questions');

            $table->comment('Таблица ответов.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_answers');
    }
};
