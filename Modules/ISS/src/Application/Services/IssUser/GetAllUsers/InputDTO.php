<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\GetAllUsers;

/**
 * @var array $returnedFields массив полей, которые хотим получить
 */

class InputDTO
{
    public function __construct(
        public array $returnedFields = ['*']
    )
    {
    }
}
