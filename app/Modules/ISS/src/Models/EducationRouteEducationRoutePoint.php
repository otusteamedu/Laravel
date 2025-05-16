<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BasePivotModel;
use App\Modules\ISS\src\Models\EducationRoutePoint;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\EducationRouteUser;
use App\Modules\ISS\database\factories\EducationRouteEducationRoutePointFactory;

class EducationRouteEducationRoutePoint extends BasePivotModel
{
    protected $fillable = ['route_point_id', 'route_id', 'exam_date'];
    //public $incrementing = true;
    protected $casts = ['exam_date' => 'datetime'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return EducationRouteEducationRoutePointFactory::new();
    }

    public function educationRoutePoint()
    {
        return $this->belongsTo(EducationRoutePoint::class, 'route_point_id');
    }

    public function educationRoute()
    {
        return $this->belongsTo(EducationRoute::class, 'route_id');
    }

    public function educationRouteUser()
    {
        return $this->hasMany(EducationRouteUser::class, 'last_pass_point_id');
    }
}
