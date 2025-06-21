<?php

namespace App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp;

use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\InputDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\OutputDTO;

class LoadUserDataFromMainApp
{

    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Загрузить данные пользователя в ИОС из основного приложения
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        $result = null;

        try {
            $issUser = $this->repository->getUserData(
                [
                    'field_name'=> 'id',
                    'field_value' => $inputData->issUserId,
                    'returned_fields' => $inputData->returnedFields
                ]
            );
            $mainAppUserId = $issUser['user_id'];
        } catch (\Error | \Exception $e) {
            $issUser = [];
            //запись в лог
        }


        if (!empty($issUser) && !is_null($issUser['user_id'])) {
            //загружаем данные из основного приложения
            try {
                $userOrganization = $this->repository->getUserDataFromMainApp(
                    [
                        'table_name' => $inputData->organization->tableName,
                        'fields' => [
                            $inputData->organization->fieldOrganizationName
                        ],
                        'field_code_name' => $inputData->organization->fieldCodeName,
                        'user_id' => $mainAppUserId,
                    ]
                );

                $userFio = $this->repository->getUserDataFromMainApp(
                    [
                        'table_name' => $inputData->fio->tableName,
                        'fields' => [
                            $inputData->fio->fieldName,
                            $inputData->fio->fieldSecondName,
                            $inputData->fio->fieldLastName
                            ],
                        'field_code_name' => $inputData->fio->fieldCodeName,
                        'user_id' => $mainAppUserId,
                    ]
                );

                $userContacts = $this->repository->getUserDataFromMainApp(
                    [
                        'table_name' => $inputData->contact->tableName,
                        'fields' => [
                            $inputData->contact->fieldEmail,
                        ],
                        'field_code_name' => $inputData->contact->fieldCodeName,
                        'user_id' => $mainAppUserId,
                    ]
                );

            } catch (\Error | \Exception $e) {
                $userFio = [];
                $userOrganization = [];
                $userContacts = [];
                $result = $e->getMessage() ? $e->getMessage() : 'unknown error';
                //запись в лог
            }

            //записываем данные из основного приложения в ИОС
            if (!empty($userFio) && !empty($userOrganization) && !empty($userContacts)) {
                try {
                    $updateResult = $this->repository->updateIssUserByMainAppData(
                        [
                            'iss_user_id' => $inputData->issUserId,
                            'name' => $userFio[$inputData->fio->fieldName],
                            'last_name' => $userFio[$inputData->fio->fieldLastName],
                            'second_name' => $userFio[$inputData->fio->fieldSecondName],
                            'organization' => $userOrganization[$inputData->organization->fieldOrganizationName],
                            'email' => $userContacts[$inputData->contact->fieldEmail],
                        ]
                    );
                } catch (\Error | \Exception $e) {
                    $updateResult = false;
                    //запись в лог
                }

                if ($updateResult !== true) {
                    $result = 'error updating iss user by main app data';
                    //запись в лог
                }
            } else {
                $result = 'error loading user data from main application';
                //запись в лог
            }
        } else {
            $result = 'iss user not found';
            //запись в лог
        }

        if (is_null($result)) {
            $result = 'ok';
        }

        return new OutputDTO($result);
    }
}
