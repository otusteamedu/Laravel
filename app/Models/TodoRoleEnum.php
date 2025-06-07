<?php

namespace App\Models;

enum TodoRoleEnum: string
{
    case RESPONSIBLE = 'Ответственный';
    case PERFORMER   = 'Исполнитель';
    case WATCHER     = 'Наблюдатель';
}
