<?php

namespace Tests\Feature\Admin\Category;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('admin-category')]
class CreateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_create_form()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->get(route('admin.categories.create'));

        $response->assertOk()
            ->assertViewIs('admin.categories.create');
    }

    public function test_it_creates_new_category()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $categoryData = [
            'name' => 'Test Category',
            'sort' => 1,
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.categories.store'), $categoryData);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', $categoryData);
    }

    public function test_it_validates_required_fields()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), []);

        $response->assertRedirect()
            ->assertInvalid(['name']);
    }

    public function test_it_validates_sort_field_type()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), [
                'name' => 'Test Category',
                'sort' => 'not-a-number'
            ]);

        $response->assertRedirect()
            ->assertInvalid(['sort']);
    }

    public function test_it_requires_authentication()
    {
        $response = $this->post(route('admin.categories.store'), []);

        $response->assertRedirect(route('login'));
    }

    public function test_it_requires_admin_role()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->post(route('admin.categories.store'), []);

        $response->assertForbidden();
    }
} 