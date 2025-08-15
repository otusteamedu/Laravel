<?php

namespace Tests\Feature\Http\Controllers\Admin\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('categories')]
class CategoryControllerTest  extends TestCase
{
    use RefreshDatabase;
    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->admin->roles()->create(['name' => 'Administrator']);
        $this->admin->roles->first()->permissions()->createMany([
            ['name' => 'create-category'],
            ['name' => 'edit-category'],
            ['name' => 'delete-category'],
            ['name' => 'view-category'],
        ]);

        $this->user = User::factory()->create();

    }

    public function test_admin_can_view_categories_index_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    public function test_admin_can_view_create_category_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.create');
    }

    public function test_admin_can_store_new_category()
    {
        $data = [
            'title' => 'Test Category',
            'alias' => 'test-category',
            'text' => 'Test description',
            'published' => true,
            'order' => 1,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), $data);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['title' => 'Test Category']);
    }

    public function test_admin_can_view_edit_category_page()
    {
        $category = Category::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.edit');
    }

    public function test_admin_can_update_category()
    {
        $category = Category::factory()->create(['user_id' => $this->admin->id]);

        $data = [
            'title' => 'Updated Category',
            'alias' => 'updated-category',
            'text' => 'Updated description',
            'published' => false,
            'order' => 2,
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.categories.update', $category), $data);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['title' => 'Updated Category']);
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_regular_user_cannot_access_category_management()
    {
        $category = Category::factory()->create(['user_id' => $this->admin->id]);

        // Index
        $response = $this->actingAs($this->user)->get(route('admin.categories.index'));
        $response->assertForbidden();

        // Create
        $response = $this->actingAs($this->user)->get(route('admin.categories.create'));
        $response->assertForbidden();

        // Store
        $response = $this->actingAs($this->user)->post(route('admin.categories.store'), []);
        $response->assertForbidden();

        // Edit
        $response = $this->actingAs($this->user)->get(route('admin.categories.edit', $category));
        $response->assertForbidden();

        // Update
        $response = $this->actingAs($this->user)->put(route('admin.categories.update', $category), []);
        $response->assertForbidden();

        // Delete
        $response = $this->actingAs($this->user)->delete(route('admin.categories.destroy', $category));
        $response->assertForbidden();
    }

}
