<?php

namespace App\Modules\ISS\src\Services\EducationRoute;

interface EducationRouteRepoInterface
{
    /**
     * Запрос БД получить все обучающие маршруты пользователя с данными о проценте их прохождения
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['user_data_id']
     * @return array
     *               [
     *                 ['route_id'=>, 'route_name'=>, 'ready_percent'=>,],
     *                 ['route_id'=>, 'route_name'=>, 'ready_percent'=>,],
     *                 ...
     *               ]
     */
    public function getUserRoutesWithPassPercent(array $inputData): array;

    /**
     * Запрос БД получить данные для всех точек всех обучающих маршрутов пользователя
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['user_data_id']
     * @return array
     *               [
     *                 ['route_id'=>, 'real_route_point_id'=>, 'exam_date'=>, 'point_name'=>, 'pass'=> ],
     *                 ['route_id'=>, 'real_route_point_id'=>, 'exam_date'=>, 'point_name'=>, 'pass'=> ],
     *                 ...
     *               ]
     */
    public function getAllRoutePointsForUser(array $inputData): array;

    /**
     * Запрос БД получить степень прохождения маршрутов обучения всеми сотрудниками фирмы для текущего менеджера или админа
     * @param array $inputData
     *              код польз-я ИОС
     *                  $inputData['user_data_id']
     *              отметка что администратор ИОС
     *                  $inputData['is_iss_admin']
     * @return array [
     *                ['user_data_id'=>, 'organization'=>, 'route_name'=>, 'ready_percent'=>, 'fio'=>],
     *                ['user_data_id'=>, 'organization'=>, 'route_name'=>, 'ready_percent'=>, 'fio'=>],
     *                ['user_data_id'=>, 'organization'=>, 'route_name'=>, 'ready_percent'=>, 'fio'=>],
     *                ...
     *               ]
     */
    public function getRouteReadyPercentForUsersOfFirm(array $inputData): array;
}
