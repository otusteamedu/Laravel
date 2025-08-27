<?php

namespace ISS\App\Infrastructure\Models;

use ISS\App\Infrastructure\Models\BaseModel;
use ISS\App\Infrastructure\Models\ExamQuestion;
use ISS\Database\Factories\ExamQuestionTypeFactory;

/**
 * Поля модели
 * @property int $id -- код типа вопроса
 * @property string $name -- название типа экзаменационного вопроса
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property \datetime $deleted_at
 */

class ExamQuestionType extends BaseModel
{
    protected $fillable = ['name'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return ExamQuestionTypeFactory::new();
    }

    //связи
    public function examQuestion()
    {
        return $this->hasMany(ExamQuestion::class, 'question_type_id');
    }
}
