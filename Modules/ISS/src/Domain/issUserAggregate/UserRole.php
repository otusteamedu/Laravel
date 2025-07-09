<?php

namespace Modules\ISS\Domain\issUserAggregate;


use Modules\ISS\Domain\issUserAggregate\RoleNameVO;
use Modules\ISS\Domain\issUserAggregate\IdVO;

/**
 * @var IdVO $id код роли пользователя ИОС
 * @var RoleNameVO $name название роли пользователя ИОС
 */

class UserRole
{
    private IdVO $id;
    private RoleNameVO $name;

    public function __construct(IdVO $id, RoleNameVO $name)
    {
        $this->id = $id;
        $this->name = $name;
    }


}
