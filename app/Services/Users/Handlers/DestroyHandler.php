<?php
namespace App\Services\Users\Handlers;

use App\Services\Users\Exceptions\UserNotFoundException;
use App\Repositories\Users\UserRepositoryInterface;

class DestroyHandler{

    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(int $id): ?bool
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        return $this->userRepository->delete($user);
    }
} 