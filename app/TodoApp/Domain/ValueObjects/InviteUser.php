<?php

namespace App\TodoApp\Domain\ValueObjects;

use App\TodoApp\Domain\ValueObjects\Email;
use App\TodoApp\Domain\ValueObjects\ProjectRoleEnum;
use App\TodoApp\Domain\ValueObjects\UserName;
use DateTimeImmutable;

final class InviteUser
{
    private ModelId $userId;
    private UserName $name;
    private Email $email;
    /** @var ProjectRoleEnum[] */
    private array $roles;
    private DateTimeImmutable $invited;
    private ?DateTimeImmutable $joined = null;
    private ?DateTimeImmutable $lefted = null;

    public function __construct(
        ModelId $userId,
        UserName $name,
        Email $email,
        array $roles,
        DateTimeImmutable $invited,
        ?DateTimeImmutable $joined = null,
        ?DateTimeImmutable $lefted = null,
    ) {
        $this->assertRolesIsValud($roles);

        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
        $this->roles = $roles;
        $this->invited = $invited;
        $this->joined = $joined;
        $this->lefted = $lefted;
    }

    /**
     * Get the value of userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the value of roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Get the value of invited
     */
    public function getInvited()
    {
        return $this->invited;
    }

    /**
     * Get the value of joined
     */
    public function getJoined()
    {
        return $this->joined;
    }

    /**
     * Get the value of lefted
     */
    public function getLefted()
    {
        return $this->lefted;
    }

    private function assertRolesIsValud($roles)
    {
        foreach ($roles as $role) {
            if (!$role instanceof ProjectRoleEnum) {
                throw new \InvalidArgumentException('Роль пользователя в проекте должна быть объектом класса App\TodoApp\Domain\ValueObjects\ProjectRoleEnum');
            }
        }
    }
}
