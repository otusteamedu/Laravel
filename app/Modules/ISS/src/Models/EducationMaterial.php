<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\EducationMaterialType;
use App\Modules\ISS\src\Models\EducationRoutePoint;
use App\Modules\ISS\database\factories\EducationMaterialFactory;

class EducationMaterial extends BaseModel
{
    /**
     * Поля модели:
     * id -- код учебного материала (unsignedBigInteger)
     * material_type_id -- ссылка на тип учебного материала (unsignedBigInteger)
     * file_path -- путь к файлу учебного материала (string)
     * point_id --ссылка на точку обучающего маршрута (из справочника) (unsignedBigInteger)
     */

    protected $fillable = ['material_type_id', 'file_path', 'point_id'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return EducationMaterialFactory::new();
    }

    public function educationMaterialType()
    {
        return $this->belongsTo(EducationMaterialType::class, 'material_type_id');
    }

    public function educationRoutePoint()
    {
        return $this->belongsTo(EducationRoutePoint::class, 'point_id');
    }
}
