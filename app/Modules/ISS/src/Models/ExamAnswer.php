<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\database\factories\ExamAnswerFactory;

class ExamAnswer extends BaseModel
{
    /**
     * Поля модели:
     * id -- код ответа на вопрос для проверочного теста (unsignedBigInteger)
     * short_answer_name -- название ответа (string)
     * answer -- текст ответа (text)
     * question_id -- код вопроса, к которому относится ответ (unsignedBigInteger)
     */

    protected $fillable = ['short_answer_name', 'answer', 'question_id'];
    protected $casts = ['answer' => 'string'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return ExamAnswerFactory::new();
    }

    public function examQuestion()
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }
}
