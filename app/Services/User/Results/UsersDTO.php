<?php
declare(strict_types=1);

namespace App\Services\User\Results;


final readonly class UsersDTO
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
