<?php

namespace ISS\App\Infrastructure\Repositories;

use ISS\App\Application\Services\EducationRoute\EducationRouteRepoInterface;
use ISS\App\Infrastructure\Models\EducationRoute;

class EducationRouteRepo implements EducationRouteRepoInterface
{
    /**
     * Запрос БД достать данные по справочному учебному маршруту по его ID
     * @param array $inputData
     *              код справочного маршрута
     *              $inputData['id']
     * @return array ['id'=>, 'name'=> , 'created_at'=> , 'updated_at'=> , 'deleted_at' => ]
     */
    public function getRouteById(array $inputData): array
    {
        return EducationRoute::where('id', $inputData['id'])->first()->toArray();
    }
}
