<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\EducationRoutePoint;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use App\Modules\ISS\database\factories\RealEducationRoutePointFactory;
use Carbon\Carbon;

/**
 * Поля модели:
 * @property integer $id -- код реальной точки учебного маршрута с датой экзамена
 * @property integer $route_point_id -- ссылка на точку учебного маршрута из справочника
 * @property integer $route_id -- ссылка на учебный маршрут
 * @property Carbon $exam_date -- дата сдачи контрольного теста по этой точке учебного маршрута
 * @property integer $position -- позиция (порядковый номер) точки в учебном маршруте
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property \datetime $deleted_at
 */

class RealEducationRoutePoint extends BaseModel
{
    protected $fillable = ['route_point_id', 'route_id', 'exam_date', 'position'];
    protected $casts = ['exam_date' => 'datetime'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return RealEducationRoutePointFactory::new();
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
