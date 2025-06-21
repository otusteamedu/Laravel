<?php

namespace Tests\Unit\Services\Queries;

use Tests\TestCase;
use App\Services\Queries\FetchAllCategories\Query;
use App\Services\Queries\FetchAllCategories\Fetcher;
use App\Services\DTO\Categories\CategoryDTO;
use App\Models\Category;
use App\Repositories\Categories\CategoryRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class FetchAllCategoriesFetcherTest extends TestCase {
    use RefreshDatabase;

    public function test_returns_paginated_categories()
    {
        // Создаем настоящие категории через фабрику
        $category1 = Category::factory()->create(
            [
                'name'        => 'Работа',
                'color'       => '#ff0000',
                'description' => 'Описание 1'
            ]
        );
        $category2 = Category::factory()->create(
            [
                'name'        => 'Дом',
                'color'       => '#00ff00',
                'description' => 'Описание 2'
            ]
        );

        $categories = [$category1, $category2];

        $repository = Mockery::mock(CategoryRepositoryInterface::class);
        $repository->shouldReceive('fetchPaginated')->with(10, 0)->andReturn($categories);
        $repository->shouldReceive('count')->andReturn(15);

        $fetcher = new Fetcher($repository);
        $query   = new Query(limit: 10, offset: 0);

        $result = $fetcher->fetch($query);

        $this->assertCount(2, $result->items);
        $this->assertEquals(15, $result->total);
        $this->assertEquals(10, $result->limit);
        $this->assertEquals(0, $result->offset);

        // Проверяем что элементы - это DTO объекты
        $this->assertInstanceOf(CategoryDTO::class, $result->items[0]);
        $this->assertEquals('Работа', $result->items[0]->name);
    }

}
