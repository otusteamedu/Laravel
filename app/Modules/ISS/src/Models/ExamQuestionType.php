<?php

namespace App\Modules\ISS\src\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\database\factories\ExamQuestionTypeFactory;

/**
 * Поля модели
 * @property int $id -- код типа вопроса
 * @property string $name -- название типа экзаменационного вопроса
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property \datetime $deleted_at
 */

class ExamQuestionType extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return ExamQuestionTypeFactory::new();
    }

    public function examQuestion()
    {
        return $this->hasMany(ExamQuestion::class, 'question_type_id');
    }
}
