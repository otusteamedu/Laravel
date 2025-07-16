<?php

namespace App\TodoApp\Domain\ValueObjects;

use App\TodoApp\Domain\ValueObjects\Email;
use App\TodoApp\Domain\ValueObjects\ProjectRoleEnum;
use App\TodoApp\Domain\ValueObjects\UserName;
use DateTime;

final class ProjectUser
{
    private ModelId $userId;
    private UserName $name;
    private Email $email;
    /** @var ProjectRoleEnum[] */
    private array $roles;
    private DateTime $invited;
    private ?DateTime $joined = null;
    private ?DateTime $lefted = null;

    public function __construct(
        ModelId $userId,
        UserName $name,
        Email $email,
        array $roles,
        DateTime $invited,
        ?DateTime $joined = null,
        ?DateTime $lefted = null,
    ) {
        $this->assertRolesIsValud($roles);

        if ($joined) {
            $this->assertJoinedIsValud($invited, $joined);
        }

        if ($lefted) {
            $this->assertLeftedIsValud($invited, $joined, $lefted);
        }

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
    public function getUserId(): ModelId
    {
        return $this->userId;
    }

    /**
     * Get the value of name
     */
    public function getName(): UserName
    {
        return $this->name;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * Get the value of roles
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Get the value of invited
     */
    public function getInvited(): DateTime
    {
        return $this->invited;
    }

    /**
     * Get the value of joined
     */
    public function getJoined(): DateTime|null
    {
        return $this->joined;
    }

    /**
     * Get the value of lefted
     */
    public function getLefted(): DateTime|null
    {
        return $this->lefted;
    }

    private function assertRolesIsValud($roles): void
    {
        foreach ($roles as $role) {
            if (!$role instanceof ProjectRoleEnum) {
                throw new \InvalidArgumentException('Роль пользователя в проекте должна быть объектом класса App\TodoApp\Domain\ValueObjects\ProjectRoleEnum');
            }
        }
    }

    private function assertJoinedIsValud(DateTime $invited, DateTime $joined): void
    {
        if ($joined < $invited) {
            throw new \InvalidArgumentException("Дата присоединения к проекту не моет быть раньше даты приглашения");
        }
    }

    private function assertLeftedIsValud(DateTime $invited, ?DateTime $joined, DateTime $lefted): void
    {
        if ($joined && $lefted < $invited) {
            throw new \InvalidArgumentException("Дата выхода из проекта не может быть раньше даты присоединения к проекту");
        } elseif ($lefted < $invited) {
            throw new \InvalidArgumentException("Дата выхода из проекта не может быть раньше даты приглашения");
        }
    }
}
