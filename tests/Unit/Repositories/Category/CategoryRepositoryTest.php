<?php

namespace Tests\Unit\Repositories\Category;

use App\Models\Category;
use App\Repositories\Category\CategoryRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private CategoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_fetch_all_returns_array_of_categories()
    {
        // Очищаем таблицу перед тестом
        Category::query()->delete();

        $categories = [
            Category::factory()->create(['name' => 'Category 1']),
            Category::factory()->create(['name' => 'Category 2'])
        ];

        $result = $this->repository->fetchAll();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Category 1', $result[0]->name);
        $this->assertEquals('Category 2', $result[1]->name);
    }

    public function test_find_returns_category_by_id()
    {
        $category = Category::factory()->create(['name' => 'Test Category']);

        $result = $this->repository->find($category->id);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('Test Category', $result->name);
    }

    public function test_find_returns_null_when_category_not_found()
    {
        $result = $this->repository->find(999);

        $this->assertNull($result);
    }

    public function test_create_returns_new_category_instance()
    {
        $result = $this->repository->create();

        $this->assertInstanceOf(Category::class, $result);
    }

    public function test_save_returns_true_on_successful_save()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('save')->once()->andReturn(true);

        $result = $this->repository->save($category);

        $this->assertTrue($result);
    }

    public function test_save_returns_false_on_failed_save()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('save')->once()->andReturn(false);

        $result = $this->repository->save($category);

        $this->assertFalse($result);
    }

    public function test_delete_returns_true_on_successful_delete()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('delete')->once()->andReturn(true);

        $result = $this->repository->delete($category);

        $this->assertTrue($result);
    }

    public function test_delete_returns_false_on_failed_delete()
    {
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('delete')->once()->andReturn(false);

        $result = $this->repository->delete($category);

        $this->assertFalse($result);
    }

    public function test_find_by_slug_returns_category()
    {
        Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);

        $result = $this->repository->findBySlug('test-category');

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('test-category', $result->slug);
    }

    public function test_find_by_slug_returns_null_when_category_not_found()
    {
        $result = $this->repository->findBySlug('non-existent-slug');

        $this->assertNull($result);
    }
}
