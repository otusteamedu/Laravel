<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\userService\UserServiceRepositoryInterface;

class UserServiceRepository implements UserServiceRepositoryInterface
{
    /**
     * Получить всех пользователей основного приложения
     * @return array
     */
    public function getAllUsersOfMainApp(): array
    {
        return User::all()->toArray();
    }

    /**
     * Получить данные пользователя основного приложения
     * @param int $userId
     * @return array
     */
    public function getUserOfMainApp(int $userId): array
    {
        return User::firstWhere('id', $userId)->toArray();
    }

    /**
     * Редактировать ФИО пользователя основного приложения
     * @param array $inputData
     *              код пользователя
     *                  $inputData['userId']
     *              фамилия пользователя
     *                   $inputData['lastName']
     *              имя пользователя
     *                  $inputData['name']
     *              отчество пользователя
     *                   $inputData['secondName']
     * @return bool
     */
    public function editUserOfMainApp(array $inputData): bool
    {
        return User::firstWhere('id', [$inputData['userId']])
            ->update(
                [
                    'name' => $inputData['name'],
                    'second_name' => $inputData['secondName'],
                    'last_name' => $inputData['lastName'],
                ]
            );
    }
}
