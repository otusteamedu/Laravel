<?php

declare(strict_types=1);

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\Roles;
use App\Domain\User\ValueObjects\Permissions;

class User
{
    private ?int $id;
    private string $name;
    private string $email;
    private string $password;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;
    private ?\DateTimeImmutable $emailVerifiedAt;
    private bool $subscribedNews;

    private Roles $roles;
   // private Permissions $permissions;

    public function __construct(
        ?int $id,
        string $name,
        string $email,
        string $password,
        //Permissions $permissions,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null,
        ?\DateTimeImmutable $emailVerifiedAt = null,
        bool $subscribedNews = false,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->emailVerifiedAt = $emailVerifiedAt;
        $this->subscribedNews = $subscribedNews;
        //$this->permissions = $permissions;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getEmailVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->emailVerifiedAt;
    }

    public function getSubscribedNews(): bool
    {
        return $this->subscribedNews;
    }


   /* public function getPermissions(): Permissions
    {
        return $this->permissions;
    }*/

    /**
     * Обновить данные пользователя (имя, email, пароль)
     */
    public function update(string $name, string $email, ?string $passwordHash = null): void
    {
        $this->name = $name;
        $this->email = $email;

        if ($passwordHash !== null) {
            $this->password = $passwordHash;
        }
        $this->updatedAt = new \DateTimeImmutable();
    }


    public function changeName(string $name): void
    {
        $this->name = $name;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function changeEmail(string $email): void
    {
        $this->email = $email;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function changePassword(string $passwordHash): void
    {
        $this->password = $passwordHash;
        $this->updatedAt = new \DateTimeImmutable();
    }


    /**
     * Установить дату подтверждения email
     */
    public function verifyEmail(?\DateTimeImmutable $verifiedAt = null): void
    {
        $this->emailVerifiedAt = $verifiedAt ?? new \DateTimeImmutable();
    }
}
