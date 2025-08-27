<?php

declare(strict_types=1);

namespace ISS\App\Application\Services\IssUser\LoadUserDataFromMainApp;

/**
 * @var string $result результат загрузки данных в ИОС из основного приложения (ok - все загрузилось, или текст ошибки)
 */

class OutputDTO
{
    public function __construct(
        public string $result
    )
    {
    }
}
