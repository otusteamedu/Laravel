<?php
declare(strict_types=1);

namespace App\Services\User\Results;

use Carbon\Carbon;

final readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
    )
    {
    }
}
