<?php

namespace App\Domain\Repositories\Project\ValueObject;

enum ProjectRoleEnum: string
{
    case ADMIN  = 'Администратор';
    case MEMBER = 'Пользователь';
}
