<?php

namespace App\Services\userService\getUser;

use App\Services\userService\getUser\inputDTO;
use App\Services\userService\getUser\OutputDTO;
use App\Services\userService\UserServiceRepositoryInterface;

class GetUser
{
    private UserServiceRepositoryInterface $repo;

    public function __construct(UserServiceRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Получить одного пользователя Основного приложения
     * @param InputDTO $dto
     * @return OutputDTO|null
     */
    public function __invoke(inputDTO $dto): OutputDTO|null
    {
        try {
            $user = $this->repo->getUserOfMainApp($dto->userId);
        } catch (\Error | \Exception $e) {
            $user = [];
        }

        if (!empty($user)) {
            return new OutputDTO(
                id: $user['id'],
                name: $user['name'],
                secondName: $user['second_name'],
                lastName: $user['last_name'],
                organization: $user['organization'],
                userRole: $user['user_role']
            );
        } else {
            return null;
        }
    }
}
