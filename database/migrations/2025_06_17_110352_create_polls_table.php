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
        Schema::create('polls', function (Blueprint $table) {
            $table->uuid('id')->unique()->comment('Идентификатор опроса.');
            $table->integer('poll_igor_id')->comment('Старый идентификатор опроса.');
            $table->string('name')->comment('Наименование опроса.');
            $table->text('description')->nullable()->comment('Описание опроса.');
            $table->text('start_text')->nullable()->comment('Сообщение при старте опроса.');
            $table->text('end_text')->nullable()->comment('Сообщение при завершении опроса.');
            $table->string('icon')->nullable()->comment('Иконка опроса.');
            $table->boolean('authorized')->default(false)->comment('Признак авторизации. true(1) - только авторизованный, false(0) - анонимный.');
            $table->tinyInteger('chart_mode')->default(0)->comment('Отображение результатов в виде графика. 0 - никогда не показывать, 1 - показывать после завершения опроса, 2 - показывать после прохождения опроса.');
            $table->timestamp('start_date')->nullable()->comment('Дата начала опроса.');
            $table->timestamp('end_date')->nullable()->comment('Дата окончания опроса.');

            $table->index('id');
            $table->index('poll_igor_id');
            $table->comment('Таблица опросов.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};
