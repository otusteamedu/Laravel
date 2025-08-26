<?php

declare(strict_types=1);

namespace App\Domain\News\Entities;

class Category
{
    private ?int $id;
    private string $name;
    private string $slug;
    private bool $isActive;
    private int $sort;

    public function __construct(
        ?int $id,
        string $name,
        string $slug,
        bool $isActive = true,
        int $sort = 0
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->isActive = $isActive;
        $this->sort = $sort;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getIsActive(): bool
    {
        return $this->isActive();
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * Обновить параметры категории
     */
    public function update(string $name, int $sort, bool $isActive, ?string $slug = null): void
    {
        $this->name = $name;
        $this->sort = $sort;
        $this->isActive = $isActive;
        if ($slug !== null) {
            $this->slug = $slug;
        }
    }

    /**
     * Активировать категорию
     */
    public function activate(): void
    {
        $this->isActive = true;
    }

    /**
     * Деактивировать категорию
     */
    public function deactivate(): void
    {
        $this->isActive = false;
    }
}
