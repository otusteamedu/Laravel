<?php

namespace Tests\Unit\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductRepository(new Product());
    }

    #[Test]
    public function it_can_get_all_products_paginated()
    {
        Product::factory()->count(15)->create();

        $result = $this->repository->getAllPaginated(10);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->total());
        $this->assertEquals(10, $result->perPage());
    }

    #[Test]
    public function it_can_find_product_by_id()
    {
        $product = Product::factory()->create();

        $result = $this->repository->find($product->id);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals($product->id, $result->id);
    }

    #[Test]
    public function it_can_create_product()
    {
        $data = [
            'title' => 'Test Product',
            'alias' => 'test-product',
            'is_sale' => true,
            'published' => true,
            'order' => 1,
            'price' => 99.99,
            //'user_id' => 1,
        ];

        $result = $this->repository->create($data);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertDatabaseHas('products', ['title' => 'Test Product']);
    }

    #[Test]
    public function it_can_update_product()
    {
        $product = Product::factory()->create();
        $data = ['title' => 'Updated Title'];

        $result = $this->repository->update($product, $data);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Updated Title', $result->title);
        $this->assertDatabaseHas('products', ['title' => 'Updated Title']);
    }

    #[Test]
    public function it_can_delete_product()
    {
        $product = Product::factory()->create();

        $result = $this->repository->delete($product);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    #[Test]
    public function it_can_sync_categories()
    {
        $product = Product::factory()->create();
        $categories = Category::factory()->count(3)->create();

        $this->repository->syncCategories($product, $categories->pluck('id')->toArray());

        $this->assertCount(3, $product->categories);
        $this->assertEquals($categories->pluck('id')->toArray(), $product->categories->pluck('id')->toArray());
    }

    #[Test]
    public function it_can_search_products()
    {
        Product::factory()->create(['title' => 'Unique Product Title']);
        Product::factory()->create(['text' => 'Unique Product Description']);

        $result = $this->repository->search('Unique');

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(2, $result->total());
    }
}
