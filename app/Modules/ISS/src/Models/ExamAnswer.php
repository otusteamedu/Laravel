<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\database\factories\ExamAnswerFactory;

/**
 * Поля модели:
 * @property integer $id -- код ответа на вопрос для проверочного теста
 * @property string $short_answer_name -- название ответа
 * @property string $answer -- текст ответа
 * @property integer $question_id -- код вопроса, к которому относится ответ
 */

class ExamAnswer extends BaseModel
{
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
