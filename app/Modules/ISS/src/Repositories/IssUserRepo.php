<?php

namespace App\Modules\ISS\src\Repositories;

use Illuminate\Support\Facades\DB;
use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Models\UserData;

class IssUserRepo implements IssUserRepoInterface
{
    /**
     * Запрос БД получить данные пользователя ИОС
     * @param array $issUserId код пользователя ИОС
     * @return array
     */
    public function getUserData(int $issUserId): array
    {
        $issUser = UserData::with('userRole')->where('id', $issUserId)->first();

        if(!is_null($issUser)){
            return $issUser->toArray();
        } else {
            return [];
        }
    }

    /**
     * Запрос БД получить ФИО пользователя из главного приложения
     * @param array $inputData
     *              название таблицы и полей где хранятся имя, фамилия и отчество сотрудника
     *                  $inputData['fio'] = ['table_name' =>'', 'field_name' => '', 'field_second_name', 'field_last_name' => '']
     *              код пользователя в основном приложении
     *                  $inputData['user_id']
     * @return array
     */
    public function getUserFioFromMainApp(array $inputData): array
    {
        $rawData = DB::select(
            'select :field_name, :field_second_name, :field_last_name from :table_name where id = :user_id',
            [
                'field_name' => $inputData['fio']['field_name'],
                'field_second_name' => $inputData['fio']['field_second_name'],
                'field_last_name' => $inputData['fio']['field_last_name'],
                'table_name' => $inputData['fio']['table_name'],
                'user_id' => $inputData['user_id']
            ]
        );

        $result = [];
        if (!empty($rawData)) {
            foreach (get_object_vars($rawData[0]) as $key => $value) {
                $result[$key] = $value;
            }
        }

        return $result;
    }


    /**
     * Запрос БД получить название организации пользователя из главного приложения
     * @param array $inputData
     *              название таблицы и поля где хранится название организации пользователя, а также код организации
     *                  $inputData['organization'] = ['table_name' =>'', 'field_organization_name' => '', 'organization_code' =>]
     * @return array
     */
    public function getUserOrganizationFromMainApp(array $inputData): array
    {
        $rawData = DB::select(
            'select :field_organization_name from :table_name where id = :organization_code',
            [
                'field_organization_name' => $inputData['organization']['field_organization_name'],
                'table_name' => $inputData['organization']['table_name'],
                'organization_code' => $inputData['organization']['organization_code'],
            ]
        );

        $result = [];
        if (!empty($rawData)) {
            foreach (get_object_vars($rawData[0]) as $key => $value) {
                $result[$key] = $value;
            }
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
     * @return array
     */
    public function updateIssUserByMainAppData(array $inputData): bool
    {
        try {
            $issUser = UserData::where('id', $inputData['iss_user_id'])->first();

            $issUser->name = $inputData['name'];
            $issUser->second_name = $inputData['second_name'];
            $issUser->last_name = $inputData['last_name'];
            $issUser->organization = $inputData['organization'];
            $issUser->save();
        } catch (\Error | \Exception $e) {
            return false;
        }

        return true;
    }
}
