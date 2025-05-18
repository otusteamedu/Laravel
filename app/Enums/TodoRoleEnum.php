<?php

namespace App\Enums;

enum TodoRoleEnum: string
{
    case CREATOR   = 'Постановщик';
    case PERFORMER = 'Исполнитель';
    case WATCHER   = 'Наблюдатель';
}
