<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;
use Illuminate\Support\Number;

#[Group('product')]
class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTheTestEnvironment();
    }

    public function test_product_list()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->getId(), 'title' => 'Bbb']);
        Product::factory()->create(['category_id' => $category->getId(), 'title' => 'Aaa']); 
        
        $url = route('admin.products.index') . '?sort=title&direction=asc';
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.products.index');
        $response->assertViewHas('products', function ($products) {
            $condition1 = $products[0]->getTitle() === 'Aaa';
            $condition2 = $products[1]->getTitle() === 'Bbb';
            return $condition1 && $condition2;
        });
        $response->assertSee('Aaa');
        $response->assertSee('Bbb');
    }

    public function test_product_show()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->getId()]);

        $url = route('admin.products.show', $product->getId());
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.products.show');
        $response->assertViewHas('productId', $product->getId());
        $response->assertViewHas('title', $product->getTitle());
        $response->assertViewHas('description', $product->getDescription());
        $response->assertViewHas('price', Number::format($product->getPrice(), locale: 'ru'));
        $response->assertViewHas('stock', $product->getStock());
        $response->assertViewHas('categoryTitle', $product->getCategory()->getTitle());
        $response->assertViewHas('assets');
        $response->assertViewHas('createdAt');
        $response->assertSee($product->getTitle());
        $response->assertSee($product->getDescription());
        $response->assertSee(Number::format($product->getPrice(), locale: 'ru'));
        $response->assertSee($product->getStock());
        $response->assertSee($product->getCategory()->getTitle());

        $url = route('admin.products.show', 100);
        $response = $this->actingAs($user)->get($url);

        $response->assertNotFound();
    }

    public function test_product_create()
    {
        $user = User::factory()->create(['role_id' => 1]);

        $url = route('admin.products.create');
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.products.create');
        $response->assertViewHas('categories');
        $response->assertSee('Название телефона');
        $response->assertSee('Описание телефона');
        $response->assertSee('Категория');
        $response->assertSee('Цена');
        $response->assertSee('Количество на складе');
        $response->assertSee('Изображения/видео');
    }

    public function test_product_store()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create();
        $data = [
            'title' => 'test title',
            'description' => 'test description',
            'category_id' => $category->getId(),
            'price' => 1000,
            'stock' => 10,
        ];

        $url = route('admin.products.store');
        $response = $this->actingAs($user)->post($url, $data);

        $redirectUrl = route('admin.products.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseHas('products', $data);
    }

    public function test_product_edit()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->getId()]);

        $url = route('admin.products.edit', $product->getId());
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.products.edit');
        $response->assertViewHas('productId', $product->getId());
        $response->assertViewHas('title', $product->getTitle());
        $response->assertViewHas('description', $product->getDescription());
        $response->assertViewHas('categoryId', $product->getCategoryId());
        $response->assertViewHas('price', $product->getPrice());
        $response->assertViewHas('stock', $product->getStock());
        $response->assertViewHas('categories');
        $response->assertSee($product->getTitle());
        $response->assertSee($product->getDescription());
        $response->assertSee($product->getPrice());
        $response->assertSee($product->getStock());
        $response->assertSee($product->getCategory()->getTitle());

        $url = route('admin.products.edit', 100);
        $response = $this->actingAs($user)->get($url);

        $response->assertNotFound();
    }

    public function test_product_update()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->getId()]);
        $newData = [
            'title' => 'test title',
            'description' => 'test description',
            'category_id' => $category->getId(),
            'price' => 1000,
            'stock' => 10,
        ];

        $url = route('admin.products.update', $product->getId());
        $response = $this->actingAs($user)->put($url, $newData);

        $redirectUrl = route('admin.products.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseHas('products', array_merge(['id' => $product->getId()], $newData));

        $url = route('admin.products.update', 100);
        $response = $this->actingAs($user)->put($url, $newData);

        $response->assertNotFound();
    }

    public function test_product_destroy()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->getId()]);

        $url = route('admin.products.destroy', $product->getId());
        $response = $this->actingAs($user)->delete($url);

        $redirectUrl = route('admin.products.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseMissing('products', ['id' => $product->getId()]);

        $url = route('admin.products.destroy', 100);
        $response = $this->actingAs($user)->delete($url);

        $response->assertNotFound();
    }
}
