<?php

namespace ISS\App\Domain\IssUser;


use ISS\App\Domain\IssUser\ValueObjects\RoleName;
use ISS\App\Domain\SharedValueObjects\Id;

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
