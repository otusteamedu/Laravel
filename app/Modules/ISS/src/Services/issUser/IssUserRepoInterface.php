<?php

namespace App\Modules\ISS\src\Services\issUser;

interface IssUserRepoInterface
{
    /**
     * Запрос БД получить данные всех пользователей ИОС
     * @param array $inputData
     *                 массив имен полей, которые хотим получить
     *                     $inputData['returned_fields']
     * @return array
     */
    public function getAllUsersData(array $inputData): array;

    /**
     * Запрос БД получить данные нескольких пользователей ИОС по заданному значению из выбранного поля
     * @param array $inputData
     *                 название поля
     *                     $inputData['field_name']
     *                 значение поля
     *                     $inputData['field_value']
     *                 массив имен полей, которые хотим получить
     *                     $inputData['returned_fields']
     * @return array
     */
    public function getManyUsersData(array $inputData): array;

    /**
     * Запрос БД получить данные одного пользователя ИОС по заданному значению из выбранного поля
     * @param array $inputData
     *                название поля
     *                    $inputData['field_name']
     *                значение поля
     *                    $inputData['field_value']
     *                массив имен полей, которые хотим получить
     *                    $inputData['returned_fields']
     * @return array
     */
    public function getUserData(array $inputData): array;

    /**
     * Запрос БД получить данные пользователя из главного приложения
     * @param array $inputData
     *              название таблицы в основном приложении откуда берем данные
     *                   $inputData['table_name'] =>'',
     *              название полей в таблице, из которых берем данные сотрудника
     *                  $inputData['fields'] = ['field_name1', 'field_name2', 'field_name3', ...]
     *              название поля в Users где хранится код первичного ключа таблицы из которой берем данные
     *                  $inputData['field_code_name']
     *              коды пользователей в основном приложении, для которых извлекааем данные
     *                  $inputData['user_ids']
     * @return array
     *         [
     *             user_id1 => [user_id => '', :field_name1 => '', :field_name2 => '', :field_name3 => ''],
     *             user_id2 => [user_id => '', :field_name1 => '', :field_name2 => '', :field_name3 => ''],
     *             ....
     *         ]
     */
    public function getUserDataFromMainApp(array $inputData): array;

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
     *                    электронная почта пользователя из основного приложения
     *                         $inputData['email']
     * @return bool
     */
    public function updateIssUserByMainAppData(array $inputData): bool;

    /**
     * Запрос БД записать в базу защитный жетон для пользователя ИОС
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['iss_user_id']
     *              токен
     *                   $inputData['web_token']
     * @return array
     */
    public function setWebToken(array $inputData): array;

    /**
     * Запрос БД удалить из базы защитный жетон для пользователя ИОС
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['iss_user_id']
     * @return array
     */
    public function delWebToken(array $inputData): array;

    /**
     * Запрос БД извлеч из базы защитный жетон для пользователя ИОС
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['iss_user_id']
     * @return array
     */
    public function fetchWebToken(array $inputData): array;
}
