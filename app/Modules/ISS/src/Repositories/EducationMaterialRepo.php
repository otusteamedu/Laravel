<?php

namespace App\Modules\ISS\src\Repositories;

use Illuminate\Support\Facades\DB;
use App\Modules\ISS\src\Services\EducationMaterial\EducationMaterialRepoInterface;
use App\Modules\ISS\src\Models\EducationMaterial;

class EducationMaterialRepo implements EducationMaterialRepoInterface
{
    /**
     * Запрос БД достать все учебные материалы для выбранной СПРАВОЧНОЙ точки обучающего маршрута
     * @param array $inputData
     *              код справочной точки обучющего маршрута
     *              $inputData['point_id']
     *              список возвращаемых полей $inputData['returned_fields']
     * @return array
     *         [
     *             ['id'=>, 'title'=> , 'material_type_id' => , .., 'education_material_type' => ['id'=>, 'name'=>]],
     *             ...
     *         ]
     */
    public  function getEducationMaterialsForRefPoint(array $inputData): array
    {
        return EducationMaterial::with('educationMaterialType')
            ->where('point_id', $inputData['point_id'])->get($inputData['returned_fields'])->toArray();
    }
}
