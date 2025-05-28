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
    public function loadUserDataFromMainApp(InputDTO $inputData): OutputDTO
    {
        $result = null;

        $issUser = $this->repository->getUserData($inputData->issUserId);

        if (!empty($issUser) && $inputData->user_id == $issUser['user_id']) {
            //загружаем данные из основного приложения
            try {
                $userFio = $this->repository->getUserFioFromMainApp(
                    [
                        'fio' => [
                            'table_name' => $inputData->fio->tableName,
                            'field_name' => $inputData->fio->fieldName,
                            'field_second_name' => $inputData->fio->fieldSecondName,
                            'field_last_name' => $inputData->fio->fieldLastName
                        ],
                        'user_id' => $inputData->user_id,
                    ]
                );
                $userOrganization = $this->repository->getUserOrganizationFromMainApp(
                    [
                        'organization' => [
                            'table_name' => $inputData->organization->tableName,
                            'field_organization_name' => $inputData->organization->fieldOrganizationName,
                            'organization_code' => $inputData->organization->organizationCode
                        ]
                    ]
                );
            } catch (\Error | \Exception $e) {
                $userFio = [];
                $userOrganization = [];
                $result = $e->getMessage() ? $e->getMessage() : 'unknown error';
                //запись в лог
            }

            //записываем данные из основного приложения в ИОС
            if (!empty($userFio) && !empty($userOrganization)) {
                $updateResult = $this->repository->updateIssUserByMainAppData(
                    [
                        'iss_user_id' => $inputData->issUserId,
                        'name' => $userFio[$inputData->fio->fieldName],
                        'last_name' => $userFio[$inputData->fio->fieldLastName],
                        'second_name' => $userFio[$inputData->fio->fieldSecondName],
                        'organization' => $userOrganization[$inputData->organization->fieldOrganizationName]
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

        return new OutputDTO($result);
    }
}
