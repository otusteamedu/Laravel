<?php

namespace Modules\ISS\Domain\issUserAggregate;


use Modules\ISS\Domain\issUserAggregate\ValueObjects\RoleName;
use Modules\ISS\Domain\SharedValueObjects\Id;

/**
 * @var Id $id код роли пользователя ИОС
 * @var RoleName $name название роли пользователя ИОС
 */

class UserRole
{
    private Id $id;
    private RoleName $name;

    public function __construct(Id $id, RoleName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): Id
    {
        return $this->id;
    }


}
