<?php
declare(strict_types=1);

namespace App\Services\User\Results;

use App\Services\User\Results\User;

final readonly class UserItems
{
    /**
     * @param User[] $results
     */
    public function __construct(
        public array $results
    )
    {
    }
}
