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
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->string('short_question_name', 200);
            $table->text('question')->nullable();
            $table->unsignedBigInteger('point_id');
            $table->foreign('point_id')->references('id')->on('education_route_points');
            $table->timestamps();
            $table->softDeletes();

            //$table->unique(['question', 'point_id']); //для mySql не индексирует строку более 256 символов!
            $table->unique(['short_question_name', 'point_id']);
            $table->index('point_id');
            $table->index('short_question_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
