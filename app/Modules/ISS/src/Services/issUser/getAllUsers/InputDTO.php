<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\getAllUsers;

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
