<?php

namespace App\Domain\News\Entities;

use App\Domain\User\Entities\User;

class News
{
    private ?int $id;
    private User $user;
    private string $name;
    private string $text;
    private ?\DateTimeImmutable $createAt;
    private string $preview;
    private string $link;
    private string $photo;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        ?int $id,
        User $user,
        string $name,
        string $text,
        ?\DateTimeImmutable $createAt = null,
        string $preview,
        string $link,
        string $photo,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->name = $name;
        $this->text = $text;
        $this->createAt = $createAt;
        $this->preview = $preview;
        $this->link = $link;
        $this->photo = $photo;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // --- Геттеры ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }    

    public function getName(): string
    {
        return $this->name;
    }

    public function getText(): string
    {
        return $this->text;
    }
    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }
    public function getPhoto(): ?string
    {
        return $this->photo;
    }
    public function getLink(): ?string
    {
        return $this->link;
    }
    public function getPreview(): ?string
    {
        return $this->preview;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // --- Сеттеры и бизнес-методы ---

    public function publish(?\DateTimeImmutable $createAt = null): void
    {
        $this->createAt = $createAt ?? new \DateTimeImmutable();
    }


    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function update(
        string $name,
        string $text,
        ?string $link = null
    ): void {
        $this->name = $name;
        $this->text = $text;
        if ($link !== null) {
            $this->link = $link;
        }
        $this->updatedAt = new \DateTimeImmutable();
    }
}
