<?php

namespace App\Services\UseCases\Commands\Auth\Socialete\AuthorizeCommand;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Hash;
use App\Services\Repositories\DTOs\UserCreateDTO;
use App\Services\Repositories\DTOs\UserSocialeteDTO;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\Repositories\UserSocialeteRepositoryInterface;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserSocialeteRepositoryInterface $userSocialeteRepository,
        private AuthManager $auth,
    ) {
        //
    }

    /**
     * Авторизация пользователя через лоин в сосети
     * Если пользователя нет в базе данных, он создается
     * @param Command $command
     * @return void
     */
    public function handle(Command $command): void
    {
        if (
            $user = $this->userSocialeteRepository->find($command->id, $command->driver)
        ) {
            $userId = $user->userId;
        } elseif (
            $user = $this->userRepository->findByEmail($command->email)
        ) {
            $userSocialete = new UserSocialeteDTO(
                userId: $user->userId,
                driver: $command->driver,
                socialiteId: $command->id
            );

            $this->userSocialeteRepository->add($userSocialete);

            $userId = $user->userId;
        } else {
            $user = new UserCreateDTO(
                name: $command->name,
                email: $command->email,
                password: Hash::make(Str::random(10)),
                email_verified_at: now(),
            );

            $userId = $this->userRepository->add($user);

            $userSocialete = new UserSocialeteDTO(
                userId: $userId,
                driver: $command->driver,
                socialiteId: $command->id
            );

            $this->userSocialeteRepository->add($userSocialete);
        }

        $user = User::query()->where('id', $userId)->firstOrFail();

        $this->auth->login($user, $command->remember);
    }
}
