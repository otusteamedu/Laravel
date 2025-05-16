<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\EducationRoutePoint;
use App\Models\User;
use App\Modules\ISS\database\factories\EducationRouteFactory;

class EducationRoute extends BaseModel
{
    protected $fillable = ['name'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return EducationRouteFactory::new();
    }

    public function educationRoutePointPivot()
    {
        return $this->belongsToMany(
            EducationRoutePoint::class,
            'education_route_education_route_point',
            'route_id',
            'route_point_id'
        );
    }

    public function userPivot()
    {
        return $this->belongsToMany(
            User::class,
            'education_route_user',
            'route_id',
            'user_id'
        );
    }

}
