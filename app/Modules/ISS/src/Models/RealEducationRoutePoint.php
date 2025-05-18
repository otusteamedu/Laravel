<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\EducationRoutePoint;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use App\Modules\ISS\database\factories\EducationRouteEducationRoutePointFactory;
use Carbon\Carbon;

class RealEducationRoutePoint extends BaseModel
{
    /**
     * Поля модели:
     * @var integer $id -- код реальной точки учебного маршрута с датой экзамена
     * @var integer $route_point_id -- ссылка на точку учебного маршрута из справочника
     * @var integer $route_id -- ссылка на учебный маршрут
     * @var Carbon $exam_date -- дата сдачи контрольного теста по этой точке учебного маршрута
     */

    protected $fillable = ['route_point_id', 'route_id', 'exam_date'];
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

    public function realEducationRoutesOfUser()
    {
        return $this->hasMany(RealEducationRoutesOfUser::class, 'last_pass_point_id');
    }
}
