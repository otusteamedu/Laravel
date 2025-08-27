<?php

namespace ISS\App\Application\Services\IssUser\GetUsersRelatedToManager;

use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Application\Services\IssUser\GetUsersRelatedToManager\InputDTO;
use ISS\App\Application\Services\IssUser\GetUsersRelatedToManager\OutputDTO;

class GetUsersRelatedToManager
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить данные пользователей ИОС, относящихся к организации текущего менеджера
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        if ($inputData->currentUser->issUserRole != config('iss.ROLE_MANAGER')) {
            return new OutputDTO(users: []);
        }

        $users = [];
        try {
            $users = $this->repository->getManyUsersData(
                [
                    'field_name' => 'organization',
                    'field_value' => $inputData->currentUser->organization,
                    'returned_fields' => $inputData->returnedFields
                ]
            );
        } catch (\Error | \Exception $e) {
            //запись в лог
        }


        return new OutputDTO(
            array_map(
                function ($currentUser) {
                    return new SingleUserDTO(
                        id: $currentUser['id'] ?? null,
                        avatarFilePath: $currentUser['user_iss_avatar_path'] ?? null,
                        userId: $currentUser['user_id'] ?? null,
                        roleId: $currentUser['role_id'] ?? null,
                        roleName: $currentUser['user_role']['name'] ?? null,
                        organization: $currentUser['organization'] ?? null,
                        name: $currentUser['name'] ?? null,
                        secondName: $currentUser['second_name'] ?? null,
                        lastName: $currentUser['last_name'] ?? null,
                        createdAt: $currentUser['created_at'] ?? null,
                        updatedAt: $currentUser['updated_at'] ?? null,
                        deletedAt: $currentUser['deleted_at'] ?? null
                    );
                },
                $users
            )
        );
    }
}
