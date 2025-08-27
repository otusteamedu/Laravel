<?php

namespace ISS\App\Infrastructure\Models;

use ISS\App\Infrastructure\Models\BaseModel;
use ISS\Database\Factories\TeacherFactory;


/**
 *  Поля модели:
 * @property $connected_organization имя организации для которой преподаватель установлен проверяющим
 * @property $teacher_email почта преподавателя, на которую будут приходить заполненные бланки экзаменов
 */

class Teacher extends BaseModel
{
    protected $fillable = ['connected_organization', 'teacher_email'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return TeacherFactory::new();
    }
}
