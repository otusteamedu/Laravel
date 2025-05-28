<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\EducationMaterial;
use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use App\Modules\ISS\database\factories\EducationRoutePointFactory;

/**
 * Поля модели:
 * @property integer id -- код точки учебного маршрута
 * @property string name -- название точки учебного маршрута (Справочник)
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property \datetime $deleted_at
 */

class EducationRoutePoint extends BaseModel
{
    protected $fillable = ['name'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return EducationRoutePointFactory::new();
    }

    public function educationMaterial()
    {
        return $this->hasMany(EducationMaterial::class, 'point_id');
    }

    public function examQuestion()
    {
        return $this->hasMany(ExamQuestion::class, 'point_id');
    }

    public function realEducationRoutePoint()
    {
        return $this->hasMany(RealEducationRoutePoint::class, 'route_point_id');
    }
}
