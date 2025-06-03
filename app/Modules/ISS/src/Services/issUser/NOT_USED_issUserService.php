<?php

namespace App\Modules\ISS\src\Services\issUser;

use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;

class issUserService
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Получить данные пользователя ИОС
     * @param array $inputData код пользователя ИОС $inputData['iss_user_id']
     * @return array
     */
    /*public function getUserData(array $inputData): array
    {
        return $this->repository->getUserData($inputData);
    }*/

    /**
     * Загрузить данные пользователя в ИОС из основного приложения
     * @param array $inputData
     *                     название таблицы и поля где хранится название организации пользователя, а также код организации
     *                             $inputData['organization'] = ['table_name' =>'',
     *                                                  'field_organization_name' => '', 'organization_code' =>],
     *                     название таблицы и полей где хранятся имя, фамилия и отчество сотрудника
     *                             $inputData['fio'] = ['table_name' =>'', 'field_name' => '',
     *                                                  'field_second_name', 'field_last_name' => '']
     *                     код пользователя в ИОС
     *                              $inputData['iss_user_id']
     *                     код пользователя в основном приложении
     *                              $inputData['user_id']
     *
     * @return string ok или текст ошибки
     */
    /*public function loadUserDataFromMainApp(array $inputData): string
    {
        $result = null;

        $issUser = $this->repository->getUserData($inputData);

        if (!empty($issUser) && $inputData['user_id'] == $issUser['user_id']) {
            //загружаем данные из основного приложения
            try {
                $userFio = $this->repository->getUserFioFromMainApp($inputData);
                $userOrgainzation = $this->repository->getUserOrganizationFromMainApp($inputData);
            } catch (\Error | \Exception $e) {
                $userFio = [];
                $userOrgainzation = [];
                $result = $e->getMessage() ? $e->getMessage() : 'unknown error';
                //запись в лог
            }

            //записываем данные из основного приложения в ИОС
            if (!empty($userFio) && !empty($userOrgainzation)) {
                $updateResult = $this->repository->updateIssUserByMainAppData(
                    [
                        'iss_user_id' => $inputData['iss_user_id'],
                        'name' => $userFio[$inputData['fio']['field_name']],
                        'last_name' => $userFio[$inputData['fio']['field_last_name']],
                        'second_name' => $userFio[$inputData['fio']['field_second_name']],
                        'organization' => $userOrgainzation[$inputData['organization']['field_organization_name']]
                    ]
                );

                if (!$updateResult) {
                    $result = 'error updating iss user by main app data';
                }
            } else {
                $result = 'error loading user data from main application';
            }
        } else {
            $result = 'iss user not found';
        }

        if (is_null($result)) {
            $result = 'ok';
        }

        return $result;
    }*/

    /**
     * Создать нового пользователя ИОС
     * @param array $inputData логин, пароль ИОС и роль пользователя в ИОС (опционно код пользователя в главном приложении)
     * @return string
     */
    public function makeNewIssUser(array $inputData): string
    {
        $result = null;
        //user_data
        return $result;
    }

    /**
     * Редактировать пользователя ИОС
     * @param array $inputData логин, пароль ИОС и роль пользователя в ИОС (опционно код пользователя в главном приложении)
     * @return string
     */
    public function updateIssUser(array $inputData): string
    {
        $result = null;
        //user_data
        return $result;
    }

    /**
     * Подключить пользователя ИОС к обучающему маршруту
     * @param array $inputData код пользователя ИОС и код маршрута ИОС
     * @return string
     */
    public function connectIssUserToRoute(array $inputData): string
    {
        $result = null;
        //real_education_routes_of_users
        return $result;
    }

    /**
     * Отключить пользователя ИОС от обучающего маршрута
     * @param array $inputData код пользователя ИОС и код маршрута ИОС
     * @return string
     */
    public function disconnectIssUserFromRoute(array $inputData): string
    {
        $result = null;
        //real_education_routes_of_users
        return $result;
    }
}
