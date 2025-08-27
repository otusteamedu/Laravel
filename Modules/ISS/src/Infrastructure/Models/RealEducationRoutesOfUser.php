<?php

namespace ISS\App\Infrastructure\Models;

use ISS\App\Infrastructure\Models\BaseModel;
use ISS\App\Infrastructure\Models\UserData;
use ISS\App\Infrastructure\Models\EducationRoute;
use ISS\App\Infrastructure\Models\RealEducationRoutePoint;
use ISS\Database\Factories\RealEducationRoutesOfUserFactory;

/**
 * Поля модели:
 * @property integer $id -- код обучающего маршрута для пользователя
 * @property integer $user_data_id -- ссылка на данные пользователя ИОС
 * @property integer $route_id -- ссылка на учебный маршрут
 * @property integer $last_pass_point_id -- ссылка последнюю на реальную точку учебного маршрута, для которой тест сдан
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property \datetime $deleted_at
 */

class RealEducationRoutesOfUser extends BaseModel
{
    protected $fillable = ['user_data_id', 'route_id', 'last_pass_point_id'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return RealEducationRoutesOfUserFactory::new();
    }

    //связи
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
