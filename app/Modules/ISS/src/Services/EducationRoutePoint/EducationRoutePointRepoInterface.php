<?php

namespace App\Modules\ISS\src\Services\EducationRoutePoint;

interface EducationRoutePointRepoInterface
{
    /**
     * Достать общие данные для точки обучающего маршрута
     * @param array $inputData
     *              код реальной точки обучающего маршрута
     *                  $inputData['id'],
     *              код пользо-я ИОС
     *                  $inputData['user_data_id']
     * @return array ['route_point_id'=>, 'exam_date'=>, 'route_name'=>, 'point_name'=>, 'last_passed_exam_date'=> ]
     */
    public function getRealPointMainData(array $inputData): array;

    /**
     * Достать видео/пдф/текстовые файлы точки обучающего маршрута
     * @param array $inputData
     *              код реальной точки обучающего маршрута,
     *                  $inputData['id']
     *              тип обучающего материала
     *                  $inputData['material_type']
     * @return array ['example\file\path\1', 'example\file\path\2', 'example\file\path\3', .....]
     */
    public function getFilesOfRealPointData(array $inputData): array;


}
