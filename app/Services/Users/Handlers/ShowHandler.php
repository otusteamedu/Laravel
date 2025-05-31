<?php
namespace App\Services\Users\Handlers;

use App\Services\Users\Exceptions\UserNotFoundException;
use App\Services\Users\Results\UserDTO;
use App\Repositories\Users\UserRepositoryInterface;

class ShowHandler
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @param int $id
     *
     * @return UserDTO
     * @throws UserNotFoundException
     */
    public function __invoke(int $id): UserDTO {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            created_at: $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : null,
            updated_at: $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : null,
        );
    }
}
