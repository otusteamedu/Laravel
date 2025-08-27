<?php

namespace ISS\App\Application\Services\EducationRoute;

interface EducationRouteRepoInterface
{
    /**
     * Запрос БД достать данные по справочному учебному маршруту по его ID
     * @param array $inputData
     *              код справочного маршрута
     *              $inputData['id']
     * @return array ['id'=>, 'name'=> , 'created_at'=> , 'updated_at'=> , 'deleted_at' => ]
     */
    public function getRouteById(array $inputData): array;
}
