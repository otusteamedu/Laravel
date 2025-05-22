<?php

namespace App\Models;

enum TodoRoleEnum: string
{
    case CREATOR   = 'Постановщик';
    case PERFORMER = 'Исполнитель';
    case WATCHER   = 'Наблюдатель';
}
