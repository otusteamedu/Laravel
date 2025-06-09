<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\fetchIssUserWebToken;

use App\Modules\ISS\src\Services\issUser\issUser;

/**
 * @var int $issUserId код пользователя ИОС
 */

class InputDTO
{
    public function __construct(
        public int $issUserId
    )
    {
    }
}
