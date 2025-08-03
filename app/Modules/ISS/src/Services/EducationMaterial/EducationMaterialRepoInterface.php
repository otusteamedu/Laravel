<?php

namespace App\Modules\ISS\src\Services\EducationMaterial;

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
    public  function getEducationMaterialsForRefPoint(array $inputData): array;
}
