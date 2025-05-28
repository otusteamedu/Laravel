<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use softDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->string('short_answer_name', 200);
            $table->text('answer')->nullable();
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('exam_questions');
            $table->string('is_right', 1)->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();

            //$table->unique(['question_id', 'answer']); //для mySql не индексирует строку более 256 символов!
            $table->unique(['short_answer_name', 'question_id']);
            $table->index('question_id');
            $table->index('short_answer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
    }
};
