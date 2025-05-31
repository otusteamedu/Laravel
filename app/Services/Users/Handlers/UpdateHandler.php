<?php
namespace App\Services\Users\Handlers;

use App\Services\Users\Commands\CommandDTO;
use App\Services\Users\Exceptions\UserNotFoundException;
use App\Services\Users\Results\UserDTO;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UpdateHandler
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @param CommandDTO $commandDTO
     *
     * @return UserDTO
     * @throws UserNotFoundException
     */
    public function __invoke(CommandDTO $commandDTO): UserDTO {
        $user = $this->userRepository->find($commandDTO->id);

        if (!$user) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        $user->name = $commandDTO->name;
        $user->email = $commandDTO->email;

        if ($commandDTO->password) {
            $user->password = Hash::make($commandDTO->password);
        }

        $this->userRepository->save($user);

        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            created_at: $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : null,
            updated_at: $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : null,
        );
    }
}
