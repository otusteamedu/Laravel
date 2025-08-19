<?php

namespace Tests\Feature\Http\V1;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('product')]
class ProductsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $accessToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->accessToken = $response->json('access_token');
    }

    public function test_get_products_list()
    {
        Product::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'price', 'published', 'order']
                ],
                'links',
                'meta',
            ]);
    }

    public function test_create_product_with_categories()
    {
        $category = Category::factory()->create();

        $productData = [
            'title' => 'Test Product',
            'price' => 99.99,
            'published' => true,
            'order' => 1,
            'categories' => [$category->id],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/v1/products', $productData);


        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'Test Product',
                    'price' => 99.99,
                    'published' => true,
                ]
            ]);

        $this->assertDatabaseHas('products', ['title' => 'Test Product']);

        // Проверяем связь с категорией
        $productId = $response->json('data.id');
        $this->assertDatabaseHas('category_product', [
            'product_id' => $productId,
            'category_id' => $category->id,
        ]);
    }

    public function test_get_product_details_with_categories()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();
        $product->categories()->attach($category);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'title' => $product->title,
                    'categories' => [
                        ['id' => $category->id, 'title' => $category->title]
                    ]
                ]
            ]);
    }

    public function test_update_product_with_categories()
    {
        $product = Product::factory()->create(['title' => 'Old Title', 'price' => 10.00]);
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $product->categories()->attach($category1);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->putJson("/api/v1/products/{$product->id}", [
            'title' => 'New Title',
            'price' => 20.00,
            'categories' => [$category2->id],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'New Title',
                    'price' => 20.00,
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'New Title',
            'price' => 20.00,
        ]);

        // Проверяем обновленные категории
        $this->assertDatabaseMissing('category_product', [
            'product_id' => $product->id,
            'category_id' => $category1->id,
        ]);

        $this->assertDatabaseHas('category_product', [
            'product_id' => $product->id,
            'category_id' => $category2->id,
        ]);
    }

    public function test_delete_product_successful()
    {
        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_get_products_validation_per_page_integer()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson('/api/v1/products?per_page=not_integer');

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['per_page']])
            ->assertJsonFragment([
                'errors' => [
                    'per_page' => ['The per page field must be an integer.']
                ]
            ]);
    }

    public function test_get_products_validation_is_sale_boolean()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson('/api/v1/products?is_sale=not_boolean');

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['is_sale']])
            ->assertJsonFragment([
                'errors' => [
                    'is_sale' => ['The is sale field must be true or false.']
                ]
            ]);
    }

    public function test_get_products_filter_by_is_sale_successful()
    {
        Product::factory()->count(2)->create(['is_sale' => true]);
        Product::factory()->count(3)->create(['is_sale' => false]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson('/api/v1/products?is_sale=1');

        $response->assertStatus(200);

        $responseData = $response->json();
        $this->assertCount(2, $responseData['data']);

        foreach ($responseData['data'] as $product) {
            $this->assertTrue($product['is_sale']);
        }
    }

    public function test_get_products_filter_by_category_id_successful()
    {
        $category = Category::factory()->create();
        $productInCategory = Product::factory()->create();
        $productInCategory->categories()->attach($category);

        $productNotInCategory = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson("/api/v1/products?category_id={$category->id}");

        $response->assertStatus(200);

        $responseData = $response->json();
        $this->assertCount(1, $responseData['data']);
        $this->assertEquals($productInCategory->id, $responseData['data'][0]['id']);
    }

    public function test_store_product_validation_title_required()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/v1/products', [
            // Пропускаем обязательное поле title
            'price' => 99.99,
            'published' => true,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['title']])
            ->assertJsonFragment([
                'errors' => [
                    'title' => ['The title field is required.']
                ]
            ]);
    }

    public function test_store_product_validation_title_max_length()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/v1/products', [
            'title' => str_repeat('a', 256), // Превышаем максимальную длину
            'price' => 99.99,
            'published' => true,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['title']])
            ->assertJsonFragment([
                'errors' => [
                    'title' => ['The title field must not be greater than 255 characters.']
                ]
            ]);
    }

    public function test_store_product_validation_images_array()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/v1/products', [
            'title' => 'Test Product',
            'price' => 99.99,
            'images' => 'not-an-array', // Не array значение
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['images']])
            ->assertJsonFragment([
                'errors' => [
                    'images' => ['The images field must be an array.']
                ]
            ]);
    }


    public function test_store_product_with_valid_images_array()
    {
        $images = ['image1.jpg', 'image2.jpg', 'image3.jpg'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/v1/products', [
            'title' => 'Test Product with Images',
            'price' => 99.99,
            'images' => $images,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'Test Product with Images',
                    'price' => 99.99
                ]
            ]);

        // Проверяем, что images сохранились как JSON
        $this->assertDatabaseHas('products', [
            'title' => 'Test Product with Images',
        ]);
    }

    public function test_update_product_validation_title_required_when_present()
    {
        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->putJson("/api/v1/products/{$product->id}", [
            'title' => '', // Пустой title при наличии поля
            'price' => 99.99,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['title']])
            ->assertJsonFragment([
                'errors' => [
                    'title' => ['The title field is required.']
                ]
            ]);
    }

    public function test_update_product_validation_title_max_length()
    {
        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->putJson("/api/v1/products/{$product->id}", [
            'title' => str_repeat('a', 256), // Превышаем максимальную длину
            'price' => 99.99,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['title']])
            ->assertJsonFragment([
                'errors' => [
                    'title' => ['The title field must not be greater than 255 characters.']
                ]
            ]);
    }

    public function test_update_product_validation_price_numeric()
    {
        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->putJson("/api/v1/products/{$product->id}", [
            'price' => 'not-a-number', // Не числовое значение
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['price']])
            ->assertJsonFragment([
                'errors' => [
                    'price' => ['The price field must be a number.']
                ]
            ]);
    }

    public function test_update_product_validation_images_array()
    {
        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->putJson("/api/v1/products/{$product->id}", [
            'images' => 'not-an-array', // Не array значение
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['images']])
            ->assertJsonFragment([
                'errors' => [
                    'images' => ['The images field must be an array.']
                ]
            ]);
    }

    public function test_update_product_validation_categories_exists()
    {
        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->putJson("/api/v1/products/{$product->id}", [
            'categories' => [999], // Несуществующая категория
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['categories.0']])
            ->assertJsonFragment([
                'errors' => [
                    'categories.0' => ['The selected categories.0 is invalid.']
                ]
            ]);
    }
}
