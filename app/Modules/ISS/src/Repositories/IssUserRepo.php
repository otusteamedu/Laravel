<?php

namespace App\Modules\ISS\src\Repositories;

use Illuminate\Support\Facades\DB;
use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Models\UserData;

class IssUserRepo implements IssUserRepoInterface
{
    /**
     * Запрос БД получить данные всех пользователей ИОС
     * @param array $inputData
     *                 массив имен полей, которые хотим получить
     *                     $inputData['returned_fields']
     * @return array
     */
    public function getAllUsersData(array $inputData): array
    {
        return UserData::with('userRole')->get($inputData['returned_fields'])->toArray();
    }

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
     public function getManyUsersData(array $inputData): array
     {
         return UserData::with('userRole')
             ->where($inputData['field_name'], $inputData['field_value'])->get($inputData['returned_fields'])->toArray();
     }

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
    public function getUserData(array $inputData): array
    {
        $issUser = UserData::with('userRole')
            ->where($inputData['field_name'], $inputData['field_value'])->first($inputData['returned_fields']);

        if(!is_null($issUser)){
            return $issUser->toArray();
        } else {
            return [];
        }
    }

    /**
     * Запрос БД получить данные пользователя из главного приложения
     * @param array $inputData
     *              название таблицы в основном приложении откуда берем данные
     *                   $inputData['table_name'] =>'',
     *              название полей в таблице, из которых берем данные сотрудника
     *                  $inputData['fields'] = ['field_name1', 'field_name2', 'field_name3', ...]
     *              название поля в Users где хранится код первичного ключа таблицы из которой берем данные
     *                  $inputData['field_code_name']
     *              код пользователя в основном приложении, для которого извлекааем данные
     *                  $inputData['user_id']
     * @return array
     *         [
     *             user_id => '', :field_name1 => '', :field_name2 => '', :field_name3 => '',
     *         ]
     */
    public function getUserDataFromMainApp(array $inputData): array
    {
        $userId = $inputData['user_id'];
        $fieldCodeName = $inputData['field_code_name'];
        $result = [];

        $rawData = DB::table($inputData['table_name'])
            ->select($inputData['fields'])
            ->addSelect(DB::raw($userId.' as user_id'))
            ->where('id', function ($q) use ($userId, $fieldCodeName) {
                $q->select($fieldCodeName)->from('users')->where('id', $userId);
            })
            ->get()->toArray();

        foreach (get_object_vars($rawData[0]) as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * Запрос БД обновить данные пользователя ИОС данными из основного приложения
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['iss_user_id']
     *              имя пользователя из основного приложения
     *                  $inputData['name']
     *              фамилия пользователя из основного приложения
     *                  $inputData['last_name']
     *              отчество пользователя из основного приложения
     *                  $inputData['second_name']
     *              название организации пользователя из основного приложения
     *                  $inputData['organization']
     *              электронная почта пользователя из основного приложения
     *                  $inputData['email']
     * @return bool
     */
    public function updateIssUserByMainAppData(array $inputData): bool
    {
            $issUser = UserData::where('id', $inputData['iss_user_id'])->first();

            $issUser->name = $inputData['name'];
            $issUser->second_name = $inputData['second_name'];
            $issUser->last_name = $inputData['last_name'];
            $issUser->organization = $inputData['organization'];
            $issUser->email = $inputData['email'];

        return $issUser->save();
    }

    /**
     * Запрос БД записать в базу защитный жетон для пользователя ИОС
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['iss_user_id']
     *              токен
     *                   $inputData['web_token']
     * @return array
     */
    public function setWebToken(array $inputData): array
    {
        $issUser = UserData::where('id', $inputData['iss_user_id'])->first();

        $issUser->web_token = $inputData['web_token'];

        return [$issUser->save()];
    }

    /**
     * Запрос БД удалить из базы защитный жетон для пользователя ИОС
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['iss_user_id']
     * @return array
     */
    public function delWebToken(array $inputData): array
    {
        $issUser = UserData::where('id', $inputData['iss_user_id'])->first();

        $issUser->web_token = null;

        return [$issUser->save()];
    }

    /**
     * Запрос БД извлеч из базы защитный жетон для пользователя ИОС
     * @param array $inputData
     *              код пользователя ИОС
     *                  $inputData['iss_user_id']
     * @return array
     */
    public function fetchWebToken(array $inputData): array
    {
        return UserData::where('id', $inputData['iss_user_id'])->first('web_token')->toArray();
    }
}
