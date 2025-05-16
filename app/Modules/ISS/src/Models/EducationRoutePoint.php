<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\EducationMaterial;
use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\database\factories\EducationRoutePointFactory;

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

    public function educationRoutePivot()
    {
        $this->belongsToMany(
            EducationRoute::class,
            'education_route_education_route_point',
            'route_point_id',
            'route_id'
        );
    }
}
