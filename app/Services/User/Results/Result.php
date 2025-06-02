<?php
declare(strict_types=1);

namespace App\Services\User\Results;

use App\Services\User\Results\UserDTO;

final readonly class Result
{
    /**
     * @param UserDTO[] $results
     */
    public function __construct(
        public array $results
    )
    {
    }
}
