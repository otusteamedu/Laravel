<?php

namespace App\TodoApp\Domain\ValueObjects;

final class FetchOptions
{
    private ?array $ids = null;
    private ?int $perPage = null;
    private ?int $page = null;

    public function __construct(
        ?array $ids = null,
        ?int $perPage = null,
        ?int $page = null,
    ) {
        $this->assertIsValidids($ids);
        $this->assertIsValidPerPage($perPage);
        $this->assertIsValidPage($page);

        $this->ids = $ids;
        $this->perPage = $perPage;
        $this->page = $page;
    }

    private function assertIsValidids($ids): void
    {
        //
    }

    private function assertIsValidPerPage($perPage): void
    {
        if ($perPage <= 0) {
            throw new \InvalidArgumentException("Количество записей на страницу должно быть натуральным числом");
        }
    }

    private function assertIsValidPage($page): void
    {
        if ($page <= 0) {
            throw new \InvalidArgumentException("Номер страницы должен быть натуральным числом");
        }
    }

    /**
     * Get the value of ids
     */
    public function getIds(): array|null
    {
        return $this->ids;
    }

    /**
     * Get the value of perPage
     */
    public function getPerPage(): int|null
    {
        return $this->perPage;
    }

    /**
     * Get the value of page
     */
    public function getPage(): int|null
    {
        return $this->page;
    }
}
