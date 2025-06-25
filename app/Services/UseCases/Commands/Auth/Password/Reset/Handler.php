<?php

namespace App\Services\UseCases\Commands\Auth\Password\Reset;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\UseCases\Commands\Auth\Password\Reset\Result;
use App\Services\UseCases\Commands\Auth\Password\Reset\Command;

class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(Command $command): ?Result
    {
        $user = $this->userRepository->findByEmail($command->email);

        if (!$user) {
            return null;
        }

        if ($command->forceReset) {
            $this->userRepository->passwordUpdate($user->userId, Str::password(12));
        }

        if ($command->sendResetLink) {
            $status = Password::sendResetLink(['email' => $command->email]);

            return new Result(routeName: $status);
        } else {
            $result = Password::sendResetLink(['email' => $command->email], function ($user, $token) {
                return $token;
            });

            if (preg_match("/^[0-9a-f]{64}$/", $result)) {
                return new Result(
                    routeName: Password::PASSWORD_RESET,
                    token: $result
                );
            } else {
                return new Result(
                    routeName: $result,
                );
            }
        }
    }
}
