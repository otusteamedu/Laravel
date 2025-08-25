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
        Schema::create('poll_answers', function (Blueprint $table) {
            $table->uuid('id')->unique()->comment('Идентификатор записи.');
            $table->uuid('identifier')->comment('Идентификатор анкеты.');
            $table->uuid('poll_id')->comment('Идентификатор опроса.');
            $table->uuid('question_id')->comment('Идентификатор вопроса.');
            $table->uuid('answer_id')->comment('Идентификатор ответа.');
            $table->ipAddress('ip')->comment('IP-адрес клиента.');
            $table->text('comment')->nullable()->comment('Комментарий вопроса..');
            $table->text('self_comment')->nullable()->comment('Комментарий ответа при типе вопроса self.');
            $table->integer('person_identifier')->nullable()->comment('Идентификатор персоны.');
            $table->integer('person_id')->nullable()->comment('Идентификатор НСИ персоны.');
            $table->integer('department_identifier')->nullable()->comment('Идентификатор подразделения.');
            $table->integer('employee_id')->nullable()->comment('Идентификатор НСИ сотрудника.');
            $table->integer('department_id')->nullable()->comment('Идентификатор НСИ подразделения.');
            $table->timestamp('created_at')->default(now())->comment('Время и дата создания записи.');

            $table->index('identifier');
            $table->index('poll_id');
            $table->index(['poll_id', 'question_id']);
            $table->index('person_id');
            $table->index('department_id');
            $table->index('created_at');

            $table->foreign('poll_id')->references('id')->on('polls');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->foreign('answer_id')->references('id')->on('question_answers');

            $table->comment('Таблица результатов опроса.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropAllViews();
        Schema::dropIfExists('poll_answers');
    }
};
