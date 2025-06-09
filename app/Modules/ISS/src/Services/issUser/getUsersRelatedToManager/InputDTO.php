<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\issUser\getUsersRelatedToManager;

use App\Modules\ISS\src\Services\issUser\IssUser;

/**
 * @var IssUser $currentUser текущий пользователь ИОС
 * @var array $returnedFields массив полей, которые хотим получить
 */

class InputDTO
{
    public function __construct(
        public IssUser $currentUser,
        public array   $returnedFields = ['*']
    )
    {
    }
}
