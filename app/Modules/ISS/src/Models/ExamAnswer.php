<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\database\factories\ExamAnswerFactory;

class ExamAnswer extends BaseModel
{
    /**
     * Поля модели:
     * @var integer $id -- код ответа на вопрос для проверочного теста
     * @var string $short_answer_name -- название ответа
     * @var string $answer -- текст ответа
     * @var integer $question_id -- код вопроса, к которому относится ответ
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
