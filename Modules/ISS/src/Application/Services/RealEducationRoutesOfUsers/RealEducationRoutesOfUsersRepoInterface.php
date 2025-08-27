<?php

namespace ISS\App\Application\Services\RealEducationRoutesOfUsers;

interface RealEducationRoutesOfUsersRepoInterface
{
    /**
     * Запрос БД достать реальный маршрут пользователя
     * (по коду справочного маршрута и коду пользователя)
     * @param array $inputData
     *              код справочного маршрута
     *              $inputData['route_id']
     *              код пользователя ИОС
     *              $inputData['user_data_id']
     * @return array
     */
    public function getRealRouteOfUserByRefRouteId(array $inputData): array;

    /**
     * Запрос БД достать все реальные маршруты пользователя
     * (по коду пользователя)
     * @param array $inputData
     *              код пользователя ИОС
     *              $inputData['user_data_id']
     * @return array
     */
    public function getAllRealRoutesOfUserByUserId(array $inputData): array;

    /**
     * Запрос БД достать все реальные маршруты всех пользователей ИОС
     * (по коду справочного маршрута)
     * @param array $inputData
     *              код справочного маршрута
     *              $inputData['route_id']
     * @return array [
     *                 ['id'=>, 'route_id'=>, 'user_data_id'=> ,'last_pass_point_id'=> , 'created_at'=>, ...],
     *                 ['id'=>, 'route_id'=>, 'user_data_id'=> ,'last_pass_point_id'=> , 'created_at'=>, ...],
     *                 ....
     *                ]
     */
    public function getAllRealRoutesByRefRouteId(array $inputData): array;

    /**
     * Запрос БД удалить обучающие маршруты пользователя иос
     * @param array $inputData
     * код пользователя ИОС
     * $inputData['iss_user_id']
     * @return array
     */
    public function deleteEducationRoutesOfIssUser(array $inputData): array;

    /**
     * Запрос БД обновить значение пройденной точки маршрута для заданного пользователя и его реального маршрута
     * @param array $inputData
     *               код реальной точки маршрута (которую заносим в real_education_route_of_users.last_pass_point)
     *                   $inputData['point_id']
     *               код реального маршрута полользователя который обновляем
     *                   $inputData['id']
     * @return bool
     */
    public function updateLastPassPoint(array $inputData): bool;
}
