<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BasePivotModel;
use App\Models\User;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\EducationRouteEducationRoutePoint;
use App\Modules\ISS\database\factories\EducationRouteUserFactory;

class EducationRouteUser extends BasePivotModel
{
    protected $fillable = ['user_id', 'route_id', 'last_pass_point_id'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return EducationRouteUserFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function educationRoute()
    {
        return $this->belongsTo(EducationRoute::class, 'route_id');
    }

    public function lastPassedPoint() //educationRouteEducationRoutePoint
    {
        return $this->belongsTo(EducationRouteEducationRoutePoint::class, 'last_pass_point_id');
    }
}
