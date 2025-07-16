<?php

namespace App\TodoApp\Domain\ValueObjects;

enum ProjectRoleEnum: string
{
    case ADMIN  = 'Администратор';
    case MEMBER = 'Пользователь';
}
