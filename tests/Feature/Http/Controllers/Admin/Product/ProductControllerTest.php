<?php

namespace Tests\Feature\Http\Controllers\Admin\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create();
        $this->admin->roles()->create(['name' => 'Administrator']);
        $this->admin->roles->first()->permissions()->createMany([
            ['name' => 'create-product'],
            ['name' => 'edit-product'],
            ['name' => 'delete-product'],
            ['name' => 'view-product'],
        ]);

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    public function test_admin_can_view_products_index_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
    }

    public function test_admin_can_view_create_product_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
        $response->assertViewHas('categories');
    }

    public function test_admin_can_store_new_product_with_images()
    {
        $image = UploadedFile::fake()->image('product.jpg');
        $images = [
            UploadedFile::fake()->image('product1.jpg'),
            UploadedFile::fake()->image('product2.jpg'),
        ];

        $data = [
            'title' => 'Test Product',
            'alias' => 'test-product',
            'text' => 'Test description',
            'image_file' => $image,
            'images_files' => $images,
            'is_sale' => true,
            'published' => true,
            'order' => 1,
            'price' => 99.99,
            'categories' => [$this->category->id],
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['title' => 'Test Product']);

        $product = Product::first();
        $this->assertNotNull($product->image);
        $this->assertCount(2, json_decode($product->images, true));
        $this->assertEquals([$this->category->id], $product->categories->pluck('id')->toArray());
    }

    public function test_admin_can_view_edit_product_page()
    {
        $product = Product::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.edit');
        $response->assertViewHas('categories');
    }

    public function test_admin_can_update_product()
    {
        $product = Product::factory()->create(['user_id' => $this->admin->id]);
        $newImage = UploadedFile::fake()->image('new-product.jpg');

        $data = [
            'title' => 'Updated Product',
            'alias' => 'updated-product',
            'text' => 'Updated description',
            'image_file' => $newImage,
            'is_sale' => false,
            'published' => false,
            'order' => 2,
            'price' => 199.99,
            'categories' => [$this->category->id],
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.products.update', $product), $data);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['title' => 'Updated Product']);

        $product->refresh();
        $this->assertNotNull($product->image);
        $this->assertEquals([$this->category->id], $product->categories->pluck('id')->toArray());
    }

    public function test_admin_can_delete_product()
    {
        $product = Product::factory()->create([
            'user_id' => $this->admin->id,
            'image' => 'products/product.jpg',
            'images' => json_encode(['products/product1.jpg', 'products/product2.jpg']),
        ]);

        //Storage::disk('public')->put('products/product.jpg', 'dummy');
        //Storage::disk('public')->put('products/product1.jpg', 'dummy');
        //Storage::disk('public')->put('products/product2.jpg', 'dummy');

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        //Storage::disk('public')->assertMissing('products/product.jpg');
        //Storage::disk('public')->assertMissing('products/product1.jpg');
        //Storage::disk('public')->assertMissing('products/product2.jpg');
    }

    public function test_regular_user_cannot_access_product_management()
    {
        $product = Product::factory()->create(['user_id' => $this->admin->id]);

        // Index
        $response = $this->actingAs($this->user)->get(route('admin.products.index'));
        $response->assertForbidden();

        // Create
        $response = $this->actingAs($this->user)->get(route('admin.products.create'));
        $response->assertForbidden();

        // Store
        $response = $this->actingAs($this->user)->post(route('admin.products.store'), []);
        $response->assertForbidden();

        // Edit
        $response = $this->actingAs($this->user)->get(route('admin.products.edit', $product));
        $response->assertForbidden();

        // Update
        $response = $this->actingAs($this->user)->put(route('admin.products.update', $product), []);
        $response->assertForbidden();

        // Delete
        $response = $this->actingAs($this->user)->delete(route('admin.products.destroy', $product));
        $response->assertForbidden();
    }
}
