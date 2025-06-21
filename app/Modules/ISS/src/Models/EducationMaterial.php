<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\EducationMaterialType;
use App\Modules\ISS\src\Models\EducationRoutePoint;
use App\Modules\ISS\database\factories\EducationMaterialFactory;

/**
 * Поля модели:
 * @property integer $id -- код учебного материала
 * @property string $title -- название учебного материала
 * @property integer $material_type_id -- ссылка на тип учебного материала
 * @property string $file_path -- путь к файлу учебного материала
 * @property integer $point_id --ссылка на точку обучающего маршрута (из справочника)
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property \datetime $deleted_at
 */

class EducationMaterial extends BaseModel
{
    protected $fillable = ['material_type_id', 'file_path', 'point_id', 'title'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return EducationMaterialFactory::new();
    }

    //связи
    public function educationMaterialType()
    {
        return $this->belongsTo(EducationMaterialType::class, 'material_type_id');
    }

    public function educationRoutePoint()
    {
        return $this->belongsTo(EducationRoutePoint::class, 'point_id');
    }
}
