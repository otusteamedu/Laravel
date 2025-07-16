<?php

namespace App\Domain\Repositories\Todo\ValueObject;

enum TodoRoleEnum: string
{
    case RESPONSIBLE = 'Ответственный';
    case PERFORMER   = 'Исполнитель';
    case WATCHER     = 'Наблюдатель';
}
