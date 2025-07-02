<?php

namespace App\Application\UseCases\Commands\Auth\Socialite\AuthorizeCommand;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthManager;
use App\Domain\Repositories\User\DTO\UserCreateDTO;
use App\Domain\Repositories\User\DTO\UserSocialiteDTO;
use App\Domain\Repositories\User\Contracts\UserRepositoryInterface;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
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
            $user = $this->userRepository->socialiteFind($command->id, $command->driver)
        ) {
            $userId = $user->userId;
        } elseif (
            $user = $this->userRepository->findByEmail($command->email)
        ) {
            $userSocialite = new UserSocialiteDTO(
                userId: $user->userId,
                driver: $command->driver,
                socialiteId: $command->id
            );

            $this->userRepository->socialiteAdd($userSocialite);

            $userId = $user->userId;
        } else {
            $user = new UserCreateDTO(
                name: $command->name,
                email: $command->email,
                password: Str::random(10),
                email_verified_at: now(),
            );

            $userId = $this->userRepository->add($user);

            $userSocialite = new UserSocialiteDTO(
                userId: $userId,
                driver: $command->driver,
                socialiteId: $command->id
            );

            $this->userRepository->socialiteAdd($userSocialite);
        }

        $user = User::query()->where('id', $userId)->firstOrFail();

        $this->auth->login($user, $command->remember);
    }
}
