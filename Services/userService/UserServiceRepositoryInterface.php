<?php

namespace App\Services\userService;

interface UserServiceRepositoryInterface
{
    /**
     * Получить всех пользователей основного приложения
     * @return array
     */
    public function getAllUsersOfMainApp(): array;

    /**
     * Получить данные пользователя основного приложения
     * @param int $userId
     * @return array
     */
    public function getUserOfMainApp(int $userId): array;

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
    public function editUserOfMainApp(array $inputData): bool;
}
