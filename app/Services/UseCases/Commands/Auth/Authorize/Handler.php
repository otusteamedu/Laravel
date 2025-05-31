<?php

namespace App\Services\UseCases\Commands\Auth\Authorize;

use Illuminate\Auth\SessionGuard;
use Illuminate\Validation\ValidationException;

class Handler
{
    public function __construct(
        private SessionGuard $aurh,
    ) {
        //
    }

    public function handle(Command $command): void
    {
        if (! $this->aurh->attempt([
            'email' => $command->email,
            'password' => $command->password
        ], $command->remember)) {

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
    }
}
