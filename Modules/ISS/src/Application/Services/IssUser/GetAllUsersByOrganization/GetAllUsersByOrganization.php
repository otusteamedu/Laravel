<?php

namespace ISS\App\Application\Services\IssUser\GetAllUsersByOrganization;

use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Application\Services\IssUser\GetAllUsersByOrganization\InputDTO;
use ISS\App\Application\Services\IssUser\GetAllUsersByOrganization\SingleUserDTO;

class GetAllUsersByOrganization
{
    private IssUserRepoInterface $repository;

    public function __construct(IssUserRepoInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Получить данные всех пользователей ИОС, относящихся к заданной организации
     * @param InputDTO $inputData
     * @return OutputDTO
     * @throws \Exception
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            $result = $this->repository->getAllUsersByOrganization(['organization' => $inputData->organization]);
        } catch (\Error | \Exception $e) {
            //запись в лог
            throw new \Exception("repo error getAllUsersByOrganization: {$e->getMessage()}");
        }

        return new OutputDTO(
            array_map(
                function ($user) {
                    return new SingleUserDTO(
                        id: $user['id'] ?? null,
                        avatarFilePath: $user['user_iss_avatar_path'] ?? null,
                        userId: $user['user_id'] ?? null,
                        roleId: $user['role_id'] ?? null,
                        roleName: $user['user_role']['name'] ?? null,
                        organization: $user['organization'] ?? null,
                        name: $user['name'] ?? null,
                        secondName: $user['second_name'] ?? null,
                        lastName: $user['last_name'] ?? null,
                        createdAt: $user['created_at'] ?? null,
                        updatedAt: $user['updated_at'] ?? null,
                        deletedAt: $user['deleted_at'] ?? null
                    );
                    },
                $result
            )
        );
    }
}
