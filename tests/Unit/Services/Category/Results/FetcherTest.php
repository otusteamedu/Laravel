<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Category\Results;

use App\Models\Category;
use App\Services\Category\Results\CategoriesDTO;
use App\Services\Category\Results\CategoryDTO;
use App\Services\Category\Results\Fetcher;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('category-results')]
class FetcherTest extends TestCase
{
    private Fetcher $fetcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fetcher = new Fetcher();
    }

    public function test_it_converts_categories_to_dtos(): void
    {
        // Arrange
        $categories = [
            $this->createCategory(1, 'First Category', 'first-category', 100),
            $this->createCategory(2, 'Second Category', 'second-category', 200),
        ];

        // Act
        $result = $this->fetcher->fetch($categories);

        // Assert
        $this->assertInstanceOf(CategoriesDTO::class, $result);
        $this->assertCount(2, $result->results);
        $this->assertContainsOnlyInstancesOf(CategoryDTO::class, $result->results);

        $this->assertEquals(1, $result->results[0]->id);
        $this->assertEquals('First Category', $result->results[0]->name);
        $this->assertEquals('first-category', $result->results[0]->slug);
        $this->assertEquals(100, $result->results[0]->sort);

        $this->assertEquals(2, $result->results[1]->id);
        $this->assertEquals('Second Category', $result->results[1]->name);
        $this->assertEquals('second-category', $result->results[1]->slug);
        $this->assertEquals(200, $result->results[1]->sort);
    }

    public function test_it_handles_empty_categories_array(): void
    {
        // Act
        $result = $this->fetcher->fetch([]);

        // Assert
        $this->assertInstanceOf(CategoriesDTO::class, $result);
        $this->assertEmpty($result->results);
    }

    private function createCategory(int $id, string $name, string $slug, int $sort): Category
    {
        $category = new Category();
        $category->id = $id;
        $category->name = $name;
        $category->slug = $slug;
        $category->sort = $sort;
        return $category;
    }
} 