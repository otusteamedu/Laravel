<?php

namespace App\Services\Repositories\Todo;

enum TodoRoleEnum: string
{
    case RESPONSIBLE = 'Ответственный';
    case PERFORMER   = 'Исполнитель';
    case WATCHER     = 'Наблюдатель';
}
