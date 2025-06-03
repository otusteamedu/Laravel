<?php

namespace App\Services\userService\getAllUsers;

use App\Services\userService\getAllUsers\OutputDTO;
use App\Services\userService\UserServiceRepositoryInterface;

class GetAllUsers
{
    private UserServiceRepositoryInterface $repo;

    public function __construct(UserServiceRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Получить данные всех пользователей Основного приложения
     * @return array<OutputDTO>
     */
    public function __invoke(): array
    {
        try {
            $users = $this->repo->getAllUsersOfMainApp();
        } catch (\Error | \Exception $e) {
            $users = [];
            //запись в лог
        }

        return array_map(
            function ($user) {
                return new OutputDTO(
                    id: $user['id'],
                    name: $user['name'],
                    secondName: $user['second_name'],
                    lastName: $user['last_name'],
                    organization: $user['organization'],
                    userRole: $user['user_role']
                );
            },
        $users
        );
    }
}
