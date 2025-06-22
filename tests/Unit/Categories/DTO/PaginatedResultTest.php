<?php
namespace Tests\Unit\Categories\DTO;

use Tests\TestCase;
use App\Services\DTO\Categories\PaginatedResult;
use App\Services\DTO\Categories\CategoryDTO;

class PaginatedResultTest extends TestCase
{

    public function test_can_create_paginated_result()
    {
        $items = [
            new CategoryDTO(1, 'Работа', '#ff0000', 'Описание', 5),
            new CategoryDTO(2, 'Дом', '#00ff00', 'Описание', 3),
        ];

        $result = new PaginatedResult(
            items: $items,
            total: 25,
            limit: 10,
            offset: 0
        );

        $this->assertCount(2, $result->items);
        $this->assertEquals(25, $result->total);
        $this->assertEquals(10, $result->limit);
        $this->assertEquals(0, $result->offset);
    }


    public function test_calculates_current_page_correctly()
    {
        // Первая страница (offset = 0)
        $result = new PaginatedResult([], 25, 10, 0);
        $this->assertEquals(1, $result->getCurrentPage());

        // Вторая страница (offset = 10)
        $result = new PaginatedResult([], 25, 10, 10);
        $this->assertEquals(2, $result->getCurrentPage());

        // Третья страница (offset = 20)
        $result = new PaginatedResult([], 25, 10, 20);
        $this->assertEquals(3, $result->getCurrentPage());
    }

    public function test_returns_per_page_limit()
    {
        $result = new PaginatedResult([], 25, 15, 0);

        $this->assertEquals(15, $result->getPerPage());
    }

    public function test_checks_if_has_more_pages()
    {
        // Есть еще страницы (offset=0, limit=10, total=25)
        $result = new PaginatedResult([], 25, 10, 0);
        $this->assertTrue($result->hasMorePages());

        // Есть еще страницы (offset=10, limit=10, total=25)
        $result = new PaginatedResult([], 25, 10, 10);
        $this->assertTrue($result->hasMorePages());

        // Последняя страница (offset=20, limit=10, total=25)
        $result = new PaginatedResult([], 25, 10, 20);
        $this->assertFalse($result->hasMorePages());

        // Точно последняя страница (offset=20, limit=10, total=30)
        $result = new PaginatedResult([], 30, 10, 20);
        $this->assertFalse($result->hasMorePages());
    }


    public function test_handles_edge_cases()
    {
        // Пустой результат
        $result = new PaginatedResult([], 0, 10, 0);
        $this->assertEquals(1, $result->getCurrentPage());
        $this->assertEquals(10, $result->getPerPage());
        $this->assertFalse($result->hasMorePages());

        // Один элемент
        $result = new PaginatedResult([], 1, 10, 0);
        $this->assertEquals(1, $result->getCurrentPage());
        $this->assertFalse($result->hasMorePages());
    }
}
