<?php

namespace ISS\App\Application\Services\EducationMaterial;

interface EducationMaterialRepoInterface
{
    /**
     * Запрос БД достать все учебные материалы для выбранной СПРАВОЧНОЙ точки обучающего маршрута
     * @param array $inputData
     *              код справочной точки обучющего маршрута
     *              $inputData['point_id']
     *              список возвращаемых полей $inputData['returned_fields']
     * @return array
     */
    public  function getAllEducationMaterialsForRefPoint(array $inputData): array;

    /**
     * Запрос БД достать все учебные материалы для выбранной СПРАВОЧНОЙ точки обучающего маршрута
     * (с фильтром по типу файла учебного материала)
     * @param array $inputData
     *              код справочной точки обучющего маршрута  $inputData['point_id']
     *              название типа файла обучающего материала $inputData['type']
     *              список возвращаемых полей $inputData['returned_fields']
     * @return array
     *         [
     *             ['id'=>, 'title'=> , 'material_type_id' => , 'file_path'=>, ],
     *             ...
     *         ]
     */
    public function  getMaterialsForRefPointFilteredByType(array $inputData): array;
}
