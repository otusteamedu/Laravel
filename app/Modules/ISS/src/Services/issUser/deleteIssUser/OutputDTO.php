<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\deleteIssUser;

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
