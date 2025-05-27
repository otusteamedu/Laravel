<?php
declare(strict_types=1);

namespace App\Services\Category\Commands;

final readonly class CommandDTO
{
    public function __construct(
        public string $name,
        public int $sort = 1,
        public int $id = 0,
    ) {
    }
}
