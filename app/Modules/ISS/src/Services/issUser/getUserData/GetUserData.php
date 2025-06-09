<?php

namespace App\Modules\ISS\src\Services\issUser\getUserData;

use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\issUser\getUserData\InputDTO;
use App\Modules\ISS\src\Services\issUser\getUserData\OutputDTO;

class GetUserData
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Получить данные пользователя ИОС
     * @param InputDTO $inputData
     * @return ?OutputDTO
     */
    public function getUserData(InputDTO $inputData): ?OutputDTO
    {
        try {
            $result = $this->repository->getUserData(['field_name' => $inputData->fieldName,
                'field_value' => $inputData->fieldValue, 'returned_fields' => $inputData->returnedFields]);
        } catch (\Error | \Exception $e) {
            return null;
            //запись в лог
        }

        if (empty($result)) {
            return null;
        } else {
            return new OutputDTO(
                id: $result['id'],
                avatarFilePath: $result['user_iss_avatar_path'],
                userId: $result['user_id'],
                roleId: $result['role_id'],
                roleName: $result['user_role']['name'],
                organization: $result['organization'],
                name: $result['name'],
                secondName: $result['second_name'],
                lastName: $result['last_name'],
                createdAt: $result['created_at'],
                updatedAt: $result['updated_at'],
                deletedAt: $result['deleted_at']
            );
        }
    }

}
