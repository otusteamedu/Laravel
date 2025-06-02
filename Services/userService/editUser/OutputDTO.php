<?php

namespace App\Services\userService\editUser;

/**
 * @var bool $result результат редактирования (true -- выполнено, false -- не выполнено)
 */

class OutputDTO
{
    public function __construct(
        public bool $result,
    )
    {
    }
}
