<?php

namespace App\Application\UseCases\User\Commands\DeleteUser;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {
    }
}
