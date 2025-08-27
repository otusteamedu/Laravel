<?php

namespace ISS\App\Infrastructure\Models;

use ISS\App\Infrastructure\Models\BaseModel;
use ISS\Database\Factories\EducationMaterialTypeFactory;

/**
 * Поля модели:
 * @property integer  $id -- код типа учебного материала
 * @property string $name -- название типа учебного материала
 * @property \datetime $created_at
 * @property \datetime $updated_at
 * @property \datetime $deleted_at
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

    //связи
    public function educationMaterial()
    {
        return $this->hasMany(EducationMaterial::class, 'material_type_id');
    }
}
