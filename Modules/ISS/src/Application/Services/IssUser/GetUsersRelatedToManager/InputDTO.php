<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\GetUsersRelatedToManager;

use ISS\App\Application\Services\IssUser\IssUser;

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
