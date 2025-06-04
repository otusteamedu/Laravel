<?php

namespace App\Services\Commands\CreateUser;

use App\Models\User;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(Command $command): bool
    {
        $user = new User();

        $user->name = $command->name;
        $user->email = $command->email;
        $user->is_admin = $command->isAdmin;

        if ($command->password) {
            $user->password = Hash::make($command->password);
        }

        return $this->userRepository->save($user);
    }
} 