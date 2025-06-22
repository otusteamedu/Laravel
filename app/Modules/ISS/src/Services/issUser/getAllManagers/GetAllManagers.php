<?php

namespace App\Modules\ISS\src\Services\issUser\getAllManagers;

use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\issUser\getAllManagers\InputDTO;
use App\Modules\ISS\src\Services\issUser\getAllManagers\OutputDTO;

class GetAllManagers
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получить данные всех пользователей ИОС
     * @param InputDTO $inputData
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        $users = [];
        try {
            $users = $this->repository->getAllManagersData(['returned_fields' => $inputData->returnedFields]);
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

