<?php

namespace App\Services\Commands\Auth\Socialete\AuthorizeCommand;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\UserSocialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function __invoke(Command $command): User
    {
        if (
            $user = $this->userSocialeteRepository->find($command->id, $command->driver)
        ) {

            Auth::login($user);
        } elseif (
            $user = $this->userRepository->findByEmail($command->email)
        ) {
            $userSocialete = new UserSocialite([
                'user_id'      => $user->id,
                'driver'       => $command->driver,
                'socialite_id' => $command->id
            ]);

            $this->userSocialeteRepository->add($userSocialete);

            Auth::login($user);
        } else {
            $user = new User([
                'name'              => $command->name,
                'email'             => $command->email,
                'email_verified_at' => now(),
                'password'          => Hash::make(Str::random(10)),
            ]);

            $this->userRepository->add($user);

            $userSocialete = new UserSocialite([
                'user_id'      => $user->id,
                'driver'       => $command->driver,
                'socialite_id' => $command->id
            ]);

            $this->userSocialeteRepository->add($userSocialete);

            Auth::login($user);
        }

        return $user;
    }
}
