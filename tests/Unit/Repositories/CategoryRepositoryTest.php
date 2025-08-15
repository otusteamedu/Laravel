<?php

namespace Tests\Unit\Repositories;

use App\Models\Category;
use App\Repositories\Eloquent\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('categories_repo')]
class CategoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CategoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository(new Category());
    }

    #[Test]
    public function it_can_get_all_categories_paginated()
    {
        Category::factory()->count(15)->create();

        $result = $this->repository->getAllPaginated(10);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->total());
        $this->assertEquals(10, $result->perPage());
    }

    #[Test]
    public function it_can_get_all_categories()
    {
        Category::factory()->count(5)->create();

        $result = $this->repository->getAll();

        $this->assertCount(5, $result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    #[Test]
    public function it_can_find_category_by_id()
    {
        $category = Category::factory()->create();

        $result = $this->repository->find($category->id);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals($category->id, $result->id);
    }

    #[Test]
    public function it_can_create_category()
    {
        $data = [
            'title' => 'Test Category',
            'alias' => 'test-category',
            'published' => true,
            'order' => 1,
            //'user_id' => 1,
        ];

        $result = $this->repository->create($data);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertDatabaseHas('categories', ['title' => 'Test Category']);
    }

    #[Test]
    public function it_can_update_category()
    {
        $category = Category::factory()->create();
        $data = ['title' => 'Updated Title'];

        $result = $this->repository->update($category, $data);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('Updated Title', $result->title);
        $this->assertDatabaseHas('categories', ['title' => 'Updated Title']);
    }

    #[Test]
    public function it_can_delete_category()
    {
        $category = Category::factory()->create();

        $result = $this->repository->delete($category);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
