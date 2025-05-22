<?php

namespace App\Models;

enum ProjectRoleEnum: string
{
    case ADMIN  = 'Администратор';
    case MEMBER = 'Пользователь';
}
