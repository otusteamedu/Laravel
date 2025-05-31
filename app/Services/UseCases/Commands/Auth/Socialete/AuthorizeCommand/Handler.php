<?php

namespace App\Services\UseCases\Commands\Auth\Socialete\AuthorizeCommand;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Services\Repositories\UserCreateDTO;
use App\Services\Repositories\UserSocialiteDTO;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\Repositories\UserSocialeteRepositoryInterface;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserSocialeteRepositoryInterface $userSocialeteRepository,
    ) {
        //
    }

    public function handle(Command $command): void
    {
        if (
            $user = $this->userSocialeteRepository->find($command->id, $command->driver)
        ) {
            $userId = $user->id;
        } elseif (
            $user = $this->userRepository->findByEmail($command->email)
        ) {
            $userSocialete = new UserSocialiteDTO(
                user_id: $user->id,
                driver: $command->driver,
                socialite_id: $command->id
            );

            $this->userSocialeteRepository->add($userSocialete);

            $userId = $user->id;
        } else {
            $user = new UserCreateDTO(
                name: $command->name,
                email: $command->email,
                password: Hash::make(Str::random(10)),
                email_verified_at: now(),
            );

            $userId = $this->userRepository->add($user);

            $userSocialete = new UserSocialiteDTO(
                user_id: $userId,
                driver: $command->driver,
                socialite_id: $command->id
            );

            $this->userSocialeteRepository->add($userSocialete);
        }

        $this->userRepository->login($userId, $command->remember);
    }
}
