<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\updateIssUser;

/**
 * @var bool $result результат операции обновления пользователя ИОС
 */

class OutputDTO
{
    public function __construct(
        public bool $result
    )
    {
    }
}
