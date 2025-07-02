<?php

namespace App\Services\UseCases\Commands\DeleteUser;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {
    }
}
