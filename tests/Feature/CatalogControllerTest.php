<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('catalog')]
class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTheTestEnvironment();
    }

    public function test_catalog_page_is_displayed(): void
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);
        $product2 = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);

        $url = route('catalog');
        $response = $this->get($url);

        $response->assertOk();
        $response->assertSee($category->getTitle());
        $response->assertSee($product1->getTitle());
        $response->assertSee($product2->getTitle());
    }

    public function test_category_page_is_displayed(): void
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);
        $product2 = Product::factory()->create(['category_id' => $category->getId(), 'stock' => 10]);

        $url = route('category', $category->getId());
        $response = $this->get($url);

        $response->assertOk();
        $response->assertSee($category->getTitle());
        $response->assertSee($product1->getTitle());
        $response->assertSee($product2->getTitle());
    }
}
