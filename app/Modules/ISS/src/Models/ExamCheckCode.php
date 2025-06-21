<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use App\Modules\ISS\src\Models\UserData;
use App\Modules\ISS\database\factories\ExamCheckCodeFactory;

/**
 * Поля модели:
 * @property $exam_check_code одноразовый сгненрированный код проверки экзамена
 * @property $iss_uer_id код пользователя ИОС, который сдает экзамен
 * @property $real_route_point_id код реальной точки учебного маршрута, к которой относится экзамен
 */

class ExamCheckCode extends BaseModel
{
    protected $fillable = ['exam_check_code', 'iss_user_id', 'real_route_point_id'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return ExamCheckCodeFactory::new();
    }

    //связи
    public function userData()
    {
        return $this->belongsTo(UserData::class, 'iss_user_id');
    }

    public function realEducationRoutePoint()
    {
        return $this->belongsTo(RealEducationRoutePoint::class, 'real_route_point_id');
    }
}
