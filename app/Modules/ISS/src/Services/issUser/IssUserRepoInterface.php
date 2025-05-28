<?php

namespace App\Modules\ISS\src\Services\issUser;

interface IssUserRepoInterface
{
    /**
     * Получает данные пользователя ИОС
     * @param array $issUserId код пользователя ИОС
     * @return array
     */
    public function getUserData(int $issUserId): array;

    /**
     * Получает ФИО пользователя из главного приложения
     * @param array $inputData
     *              название таблицы и полей где хранятся имя, фамилия и отчество сотрудника
     *                  $inputData['fio'] = ['table_name' =>'', 'field_name' => '', 'field_second_name', 'field_last_name' => '']
     *              код пользователя в основном приложении
     *                  $inputData['user_id']
     * @return array
     */
    public function getUserFioFromMainApp(array $inputData): array;

    /**
     * Получает название организации пользователя из главного приложения
     * @param array $inputData
     *              название таблицы и поля где хранится название организации пользователя, а также код организации
     *                  $inputData['organization'] = ['table_name' =>'', 'field_organization_name' => '', 'organization_code' =>]
     * @return array
     */
    public function getUserOrganizationFromMainApp(array $inputData): array;

    /**
     * Обновляет данные пользователя ИОС данными из основного приложения
     * @param array $inputData
     *                    код пользователя ИОС
     *                        $inputData['iss_user_id']
     *                    имя пользователя из основного приложения
     *                        $inputData['name']
     *                    фамилия пользователя из основного приложения
     *                        $inputData['last_name']
     *                    отчество пользователя из основного приложения
     *                         $inputData['second_name']
     *                    название организации пользователя из основного приложения
     *                         $inputData['organization']
     * @return array
     */
    public function updateIssUserByMainAppData(array $inputData): bool;
}
