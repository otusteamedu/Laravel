<?php

declare(strict_types=1);

namespace App\Http\Resources\Models;

class AuthorApiModel
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
    ) {}
}
