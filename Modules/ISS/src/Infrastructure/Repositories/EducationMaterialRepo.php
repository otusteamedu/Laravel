<?php

namespace ISS\App\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use ISS\App\Application\Services\EducationMaterial\EducationMaterialRepoInterface;
use ISS\App\Infrastructure\Models\EducationMaterial;

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
    public  function getAllEducationMaterialsForRefPoint(array $inputData): array
    {
        return EducationMaterial::with('educationMaterialType')
            ->where('point_id', $inputData['point_id'])->get($inputData['returned_fields'])->toArray();
    }

    /**
     * Запрос БД достать все учебные материалы для выбранной СПРАВОЧНОЙ точки обучающего маршрута
     * (с фильтром по типу файла учебного материала)
     * @param array $inputData
     *              код справочной точки обучющего маршрута  $inputData['point_id']
     *              название типа файла обучающего материала $inputData['type_name']
     *              список возвращаемых полей $inputData['returned_fields']
     * @return array
     *         [
     *             ['id'=>, 'title'=> , 'material_type_id' => , 'file_path'=>, ],
     *             ...
     *         ]
     */
    public function  getMaterialsForRefPointFilteredByType(array $inputData): array
    {
        return EducationMaterial::where('point_id', $inputData['point_id'])
            ->selectedType($inputData['type_name'])->get($inputData['returned_fields'])->toArray();
    }
}
