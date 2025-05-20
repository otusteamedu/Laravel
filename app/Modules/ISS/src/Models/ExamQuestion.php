<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\EducationRoutePoint;
use App\Modules\ISS\src\Models\ExamAnswer;
use App\Modules\ISS\database\factories\ExamQuestionFactory;

/**
 * Поля модели:
 * @property integer $id -- код вопроса для проверочного теста
 * @property string $short_question_name -- название вопроса
 * @property string $question -- текст вопроса
 * @property integer $point_id -- код точки учебного маршрута из справочника
 */

class ExamQuestion extends BaseModel
{
    protected $fillable = ['short_question_name', 'question', 'point_id'];
    protected $casts = ['question' => 'string'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return ExamQuestionFactory::new();
    }

    public function educationRoutePoint()
    {
        return $this->belongsTo(EducationRoutePoint::class, 'point_id');
    }

    public function examAnswer()
    {
        return $this->hasMany(ExamAnswer::class, 'question_id');
    }
}
