<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\src\Models\EducationMaterialType;
use App\Modules\ISS\src\Models\EducationRoutePoint;
use App\Modules\ISS\database\factories\EducationMaterialFactory;

class EducationMaterial extends BaseModel
{
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
