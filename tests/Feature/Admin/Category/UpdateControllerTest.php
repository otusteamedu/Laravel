<?php

namespace Tests\Feature\Admin\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('admin-category')]
class UpdateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_edit_form()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.categories.edit', ['categoryId' => $category->id]));

        $response->assertOk()
            ->assertViewIs('admin.categories.edit')
            ->assertViewHas('category');
    }

    public function test_it_updates_category()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();
        $updateData = [
            'name' => 'Updated Category',
            'sort' => 2,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.categories.update', ['categoryId' => $category->id]), $updateData);

        $response->assertRedirect(route('admin.categories.show', ['categoryId' => $category->id]));
        $this->assertDatabaseHas('categories', array_merge(
            ['id' => $category->id],
            $updateData
        ));
    }

    public function test_it_validates_required_fields()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('admin.categories.edit', ['categoryId' => $category->id]))
            ->put(route('admin.categories.update', ['categoryId' => $category->id]), []);

        $response->assertRedirect()
            ->assertInvalid(['name']);
    }

    public function test_it_validates_sort_field_type()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('admin.categories.edit', ['categoryId' => $category->id]))
            ->put(route('admin.categories.update', ['categoryId' => $category->id]), [
                'name' => 'Updated Category',
                'sort' => 'not-a-number'
            ]);

        $response->assertRedirect()
            ->assertInvalid(['sort']);
    }

    public function test_it_returns_404_for_non_existent_category()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->put(route('admin.categories.update', ['categoryId' => 999]), [
                'name' => 'Updated Category',
                'sort' => 1
            ]);

        $response->assertNotFound();
    }

    public function test_it_requires_authentication()
    {
        $category = Category::factory()->create();

        $response = $this->put(route('admin.categories.update', ['categoryId' => $category->id]), [
            'name' => 'Updated Category',
            'sort' => 1
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_it_requires_admin_role()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->put(route('admin.categories.update', ['categoryId' => $category->id]), [
                'name' => 'Updated Category',
                'sort' => 1
            ]);

        $response->assertForbidden();
    }

    public function test_it_preserves_slug_on_name_update()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create([
            'name' => 'Original Name',
            'slug' => 'original-name'
        ]);

        $response = $this->actingAs($user)
            ->put(route('admin.categories.update', ['categoryId' => $category->id]), [
                'name' => 'Updated Name',
                'sort' => 1
            ]);

        $response->assertRedirect(route('admin.categories.show', ['categoryId' => $category->id]));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Name',
            'slug' => 'original-name'
        ]);
    }

    public function test_it_preserves_other_fields_on_partial_update()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $category = Category::factory()->create([
            'name' => 'Original Name',
            'sort' => 5
        ]);

        $response = $this->actingAs($user)
            ->put(route('admin.categories.update', ['categoryId' => $category->id]), [
                'name' => 'Updated Name'
            ]);

        $response->assertRedirect(route('admin.categories.show', ['categoryId' => $category->id]));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Name',
            'sort' => 5
        ]);
    }
} 