<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use App\Modules\ISS\database\factories\EducationRouteFactory;

class EducationRoute extends BaseModel
{
    /**
     * Поля модели:
     * @var integer $id -- код учебного маршрута
     * @var string $name -- название учебного маршрута
     */

    protected $fillable = ['name'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return EducationRouteFactory::new();
    }

    public function realEducationRoutePoint()
    {
        return $this->hasMany(RealEducationRoutePoint::class, 'route_id');
    }

    public function realEducationRoutesOfUser()
    {
        return $this->hasMany(RealEducationRoutesOfUser::class, 'route_id');
    }

}
