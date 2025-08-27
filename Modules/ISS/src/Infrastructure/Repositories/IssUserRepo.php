<?php

namespace ISS\App\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Infrastructure\Models\UserData;
use ISS\App\Infrastructure\Models\UserRole;

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
     * Запрос БД получить данные всех пользователей ИОС, имеющих роль менеджер
     * @param array $inputData
     *                 массив имен полей, которые хотим получить
     *                     $inputData['returned_fields']
     * @return array
     */
    public function getAllManagersData(array $inputData): array
    {
        return UserData::where(
            'role_id',
            function ($q) {
                $q->select('id')->from('user_roles')->where('name', '=', config('iss.ROLE_MANAGER'));
            }
        )->get($inputData['returned_fields'])->toArray();
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

    /**
     * Запрос БД удалить пользователя иос
     * @param array $inputData
     * код пользователя ИОС
     * $inputData['iss_user_id']
     * @return array
     */
    public function deleteIssUser(array $inputData): array
    {
        $result = UserData::where('id', $inputData['iss_user_id'])->forceDelete();
        return [$result];
    }

    /**
     * Запрос БД найти роль пользователя иос по ее названию
     * @param array $inputData
     * название роли пользователя ИОС
     * $inputData['name']
     * @return array
     */
    public function findIssUserRoleByName(array $inputData): array //напрашивается перевод в scope в модель
    {
        return UserRole::where('name', $inputData['name'])->first()->toArray();
    }

    /**
     * Запрос БД обновить данные пользователя иос
     * @param array $inputData
     * Код пользователя ИОС
     * $inputData['id', '', ... все или некоторые поля модели]
     * @return array
     */
    public function updateIssUser(array $inputData): array
    {
        $targetUser = UserData::where('id', $inputData['id'])->first();
        foreach ($inputData as $key => $value) {
            if ($key !== 'id') {
                $targetUser->{$key} = $value;
            }
        }
        return [$targetUser->save()];
    }

    /**
     * Запрос БД создать пользователя иос
     * @param array $inputData
     * Код пользователя ИОС
     * $inputData['id', '', ... все или некоторые поля модели]
     * @return array
     */
    public function createIssUser(array $inputData): array
    {
        $dataForNewUser = [];
        foreach ($inputData as $key => $value) {
            if ($key !== 'id') {
                $dataForNewUser[$key] = $value;
            }
        }

        $newUser = UserData::create($dataForNewUser);

        return $newUser->toArray();
    }

    /**
     * Запрос БД достать роль пользователя иос по коду пользователя
     * @param array $inputData
     * Код пользователя ИОС
     * $inputData['id']
     * @return array
     */
    public function getIssUserRole(array $inputData): array
    {
        return UserData::where('id', $inputData['id'])->first()->userRole()->first()->toArray();
    }

    /**
     * Запрос БД достать всех позльзователей ИОС, относящихся к заданной организации
     * @param array $inputData
     * Код пользователя ИОС
     * $inputData['organization']
     * @return array
     */
    public function getAllUsersByOrganization(array $inputData): array
    {
        return UserData::where('organization', $inputData['organization'])->get()->toArray();
    }

}
