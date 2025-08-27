<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\DeleteIssUser;

/**
 * @var bool $result результат удаления true\false
*/

class OutputDTO
{
    public function __construct(
        public bool $result,
    )
    {
    }
}
