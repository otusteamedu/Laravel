<?php
namespace App\Services\Users\Handlers;

use App\Repositories\Users\UserRepositoryInterface;
use App\Services\Users\Commands\CommandDTO;
use Illuminate\Support\Facades\Hash;

class CreateHandler{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(CommandDTO $commandDTO): bool
    {
        $user = $this->userRepository->create();

        $user->name = $commandDTO->name;
        $user->email = $commandDTO->email;
        
        if ($commandDTO->password) {
            $user->password = Hash::make($commandDTO->password);
        }

        return $this->userRepository->save($user);
    }
} 