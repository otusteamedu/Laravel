<?php

namespace App\Modules\ISS\src\Models;

use App\Modules\ISS\src\Models\BaseModel;
use App\Modules\ISS\database\factories\EducationMaterialTypeFactory;

/**
 * Поля модели:
 * @property integer  $id -- код типа учебного материала
 * @property string $name -- название типа учебного материала
 */

class EducationMaterialType extends BaseModel
{
    protected $fillable = ['name'];

    /**
     * Переопределил метод трейта чтобы расположить фабрику в произвольной папке
     */
    protected static function newFactory()
    {
        return EducationMaterialTypeFactory::new();
    }

    public function educationMaterial()
    {
        return $this->hasMany(EducationMaterial::class, 'material_type_id');
    }
}
