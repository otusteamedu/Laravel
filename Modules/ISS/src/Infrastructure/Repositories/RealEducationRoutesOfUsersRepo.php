<?php

namespace ISS\App\Infrastructure\Repositories;

use ISS\App\Application\Services\RealEducationRoutesOfUsers\RealEducationRoutesOfUsersRepoInterface;
use ISS\App\Infrastructure\Models\RealEducationRoutesOfUser;

class RealEducationRoutesOfUsersRepo implements RealEducationRoutesOfUsersRepoInterface
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
    public function getRealRouteOfUserByRefRouteId(array $inputData): array
    {
        return RealEducationRoutesOfUser::where('route_id', $inputData['route_id'])
            ->where('user_data_id', $inputData['user_data_id'])->first()->toArray();
    }

    /**
     * Запрос БД достать все реальные маршруты пользователя
     * (по коду пользователя)
     * @param array $inputData
     *              код пользователя ИОС
     *              $inputData['user_data_id']
     * @return array
     */
    public function getAllRealRoutesOfUserByUserId(array $inputData): array
    {
        return RealEducationRoutesOfUser::where('user_data_id', $inputData['user_data_id'])->get()->toArray();
    }

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
    public function getAllRealRoutesByRefRouteId(array $inputData): array
    {
        return RealEducationRoutesOfUser::where('route_id', $inputData['route_id'])->get()->toArray();
    }

    /**
     * Запрос БД удалить обучающие маршруты пользователя иос
     * @param array $inputData
     * код пользователя ИОС
     * $inputData['iss_user_id']
     * @return array
     */
    public function deleteEducationRoutesOfIssUser(array $inputData): array
    {
        $result = RealEducationRoutesOfUser::where('user_data_id', $inputData['iss_user_id'])->forceDelete();
        return [$result];
    }

    /**
     * Запрос БД обновить значение пройденной точки маршрута для заданного пользователя и его реального маршрута
     * @param array $inputData
     *               код реальной точки маршрута (которую заносим в real_education_route_of_users.last_pass_point)
     *                   $inputData['point_id']
     *               код реального маршрута полользователя который обновляем
     *                   $inputData['id']
     * @return bool
     */
    public function updateLastPassPoint(array $inputData): bool
    {
        $realRoute = RealEducationRoutesOfUser::find($inputData['id']);
        $realRoute->last_pass_point_id = $inputData['point_id'];

        return $realRoute->save();
    }
}
