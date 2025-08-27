<?php

namespace ISS\App\Infrastructure\Models;

use ISS\App\Infrastructure\Models\BaseModel;
use ISS\App\Infrastructure\Models\RealEducationRoutePoint;
use ISS\App\Infrastructure\Models\RealEducationRoutesOfUser;
use ISS\Database\Factories\EducationRouteFactory;

/**
 * Поля модели:
 * @property integer $id -- код учебного маршрута
 * @property string $name -- название учебного маршрута
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property \datetime $deleted_at
 */

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

    //связи
    public function realEducationRoutePoint()
    {
        return $this->hasMany(RealEducationRoutePoint::class, 'route_id');
    }

    public function realEducationRoutesOfUser()
    {
        return $this->hasMany(RealEducationRoutesOfUser::class, 'route_id');
    }

}
