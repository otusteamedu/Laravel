<?php

namespace App\Enums;

enum ProjectRoleEnum: string
{
    case ADMIN  = 'Администратор';
    case MEMBER = 'Пользователь';
}
