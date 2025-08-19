<?php

namespace Tests\Feature\Http\V1;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('category')]
class CategoryControllerTest extends TestCase
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

    public function test_get_categories_list()
    {
        Category::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'alias', 'published', 'order']
                ],
                'links',
                'meta',
            ]);
    }

    public function test_create_category_successful()
    {
        $categoryData = [
            'title' => 'Test Category',
            'published' => true,
            'order' => 1,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/v1/categories', $categoryData);

        //$response->ddJson();

        $response->assertJsonStructure(['data' => ['title', 'published']]);

        $response->assertStatus(201)
            ->assertJson(
                fn(AssertableJson $json) => $json->has('data')->first(
                    fn (AssertableJson $json) =>
                    $json->has('id')
                        ->where('title', $categoryData['title'])
                        ->where('published', true)
                        ->etc()
                ));;

        $this->assertDatabaseHas('categories', ['title' => 'Test Category']);
    }

    public function test_get_category_details()
    {
        $category = Category::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson("/api/v1/categories/{$category->id}");

        //$response->ddJson();

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'title' => $category->title,
                ]
            ]);
    }

    public function test_update_category_successful()
    {
        $category = Category::factory()->create(['title' => 'Old Title']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->putJson("/api/v1/categories/{$category->id}", [
            'title' => 'New Title',
            'published' => false,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'New Title',
                    'published' => false,
                ]
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'title' => 'New Title',
        ]);
    }

    public function test_delete_category_successful()
    {
        $category = Category::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->deleteJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_create_category_validation_errors()
    {
        // Попытка создать категорию без обязательного поля title
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/v1/categories', [
            'published' => true,
            'order' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['title']]);
    }

    public function test_get_categories_with_products()
    {
        // Создаем категории с продуктами
        $categories = Category::factory()
            ->count(3)
            ->hasProducts(2)
            ->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson('/api/v1/categories?with_products=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'alias',
                        'published',
                        'order',
                        'products' => [
                            '*' => ['id', 'title', 'price', 'image']
                        ]
                    ]
                ],
                'links',
                'meta',
            ]);

        // Проверяем, что продукты загружены для каждой категории
        $responseData = $response->json();
        foreach ($responseData['data'] as $category) {
            $this->assertArrayHasKey('products', $category);
            $this->assertCount(2, $category['products']);
        }
    }

    public function test_get_categories_filter_published_true()
    {
        // Создаем опубликованные и неопубликованные категории
        Category::factory()->count(2)->create(['published' => true]);
        Category::factory()->count(3)->create(['published' => false]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->getJson('/api/v1/categories?published=1');

        $response->assertStatus(200);

        $responseData = $response->json();

        // Проверяем, что все возвращенные категории опубликованы
        foreach ($responseData['data'] as $category) {
            $this->assertTrue($category['published']);
        }

        // Проверяем, что вернулись только опубликованные (2 из 5)
        $this->assertCount(2, $responseData['data']);
    }

    public function test_store_category_validation_title_required()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/v1/categories', [
            // Пропускаем обязательное поле title
            'alias' => 'test-category',
            'published' => true,
            'order' => 1
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['title']])
            ->assertJsonFragment([
                'errors' => [
                    'title' => ['The title field is required.']
                ]
            ]);
    }

    public function test_store_category_validation_title_max_length()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->postJson('/api/v1/categories', [
            'title' => str_repeat('a', 256), // Превышаем максимальную длину
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

    public function test_update_category_validation_title_required_when_present()
    {
        $category = Category::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->putJson("/api/v1/categories/{$category->id}", [
            'title' => '', // Пустой title при наличии поля
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

    public function test_update_category_validation_title_max_length()
    {
        $category = Category::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->putJson("/api/v1/categories/{$category->id}", [
            'title' => str_repeat('a', 256), // Превышаем максимальную длину
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

}
