<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\UserData;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use App\Modules\ISS\database\factories\EducationRouteUserFactory;

class RealEducationRoutesOfUser extends BaseModel
{
    /**
     * Поля модели:
     * id -- код обучающего маршрута для пользователя (unsignedBigInteger)
     * user_data_id -- ссылка на данные пользователя ИОС (unsignedBigInteger)
     * route_id -- ссылка на учебный маршрут (unsignedBigInteger)
     * last_pass_point_id -- ссылка последнюю на реальную точку учебного маршрута, для которой тест сдан (unsignedBigInteger)
     */

    protected $fillable = ['user_data_id', 'route_id', 'last_pass_point_id'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return EducationRouteUserFactory::new();
    }

    public function userData()
    {
        return $this->belongsTo(UserData::class, 'user_data_id');
    }

    public function educationRoute()
    {
        return $this->belongsTo(EducationRoute::class, 'route_id');
    }

    public function lastPassedPoint()
    {
        return $this->belongsTo(RealEducationRoutePoint::class, 'last_pass_point_id');
    }
}
