<?php

namespace ISS\App\Application\Services\IssUser\GetUserData;

use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Application\Services\IssUser\GetUserData\InputDTO;
use ISS\App\Application\Services\IssUser\GetUserData\OutputDTO;

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
    public function __invoke(InputDTO $inputData): ?OutputDTO
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
                id: $result['id'] ?? null,
                avatarFilePath: $result['user_iss_avatar_path'] ?? null,
                userId: $result['user_id'] ?? null,
                roleId: $result['role_id'] ?? null,
                roleName: $result['user_role']['name'] ?? null,
                organization: $result['organization'] ?? null,
                name: $result['name'] ?? null,
                secondName: $result['second_name'] ?? null,
                lastName: $result['last_name'] ?? null,
                email: $result['email'] ?? null,
                createdAt: $result['created_at'] ?? null,
                updatedAt: $result['updated_at'] ?? null,
                deletedAt: $result['deleted_at'] ?? null,
            );
        }
    }

}
