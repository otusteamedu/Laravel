<?php

namespace App\Domain\News\Entities;

use App\Domain\User\Entities\User;

class News
{
    private ?int $id;
    private User $author;
    private Category $category;
    private string $title;
    private string $content;
    private ?\DateTimeImmutable $publishedAt;
    private bool $isDraft;
    private ?string $thumbnail;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        ?int $id,
        User $author,
        Category $category,
        string $title,
        string $content,
        ?\DateTimeImmutable $publishedAt = null,
        bool $isDraft = false,
        ?string $thumbnail = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->author = $author;
        $this->category = $category;
        $this->title = $title;
        $this->content = $content;
        $this->publishedAt = $publishedAt;
        $this->isDraft = $isDraft;
        $this->thumbnail = $thumbnail;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // --- Геттеры ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function isDraft(): bool
    {
        return $this->isDraft;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
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

    public function publish(?\DateTimeImmutable $publishedAt = null): void
    {
        $this->isDraft = false;
        $this->publishedAt = $publishedAt ?? new \DateTimeImmutable();
    }

    public function moveToDraft(): void
    {
        $this->isDraft = true;
        $this->publishedAt = null;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function update(
        string $title,
        string $content,
        Category $category,
        ?string $thumbnail = null
    ): void {
        $this->title = $title;
        $this->content = $content;
        $this->category = $category;
        if ($thumbnail !== null) {
            $this->thumbnail = $thumbnail;
        }
        $this->updatedAt = new \DateTimeImmutable();
    }
}
