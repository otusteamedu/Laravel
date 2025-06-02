<?php
declare(strict_types=1);

namespace App\Services\News\Results;

use Carbon\Carbon;

final readonly class AuthorDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
    )
    {
    }
}
