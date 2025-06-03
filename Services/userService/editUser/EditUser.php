<?php

namespace App\Services\userService\editUser;

use App\Services\userService\editUser\InputDTO;
use App\Services\userService\editUser\OutputDTO;
use App\Services\userService\UserServiceRepositoryInterface;

class EditUser
{
    private UserServiceRepositoryInterface $repo;

    public function __construct(UserServiceRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(InputDTO $dto): bool
    {
        try {
            $result = $this->repo->editUserOfMainApp(
                [
                    'userId' => $dto->userId,
                    'lastName' => $dto->lastName,
                    'name' => $dto->name,
                    'secondName' => $dto->secondName
                    ]
            );
        } catch (\Error | \Exception $e) {
            $result = false;
        }

        return $result;
    }
}
