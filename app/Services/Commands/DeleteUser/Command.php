<?php

namespace App\Services\Commands\DeleteUser;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {
    }
} 