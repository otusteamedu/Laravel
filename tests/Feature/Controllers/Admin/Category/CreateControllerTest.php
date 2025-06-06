<?php

namespace Feature\Controllers\Admin\Category;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('admin-category')]
class CreateControllerTest extends TestCase
{
    use RefreshDatabase;

    private const ROUTE_ADMIN_CATEGORY_INDEX = 'admin.categories.index';
    private const ROUTE_ADMIN_CATEGORY_STORE = 'admin.categories.store';
    private const ROUTE_ADMIN_CATEGORY_CREATE = 'admin.categories.create';
    private const ROUTE_LOGIN = 'login';

    public function test_it_displays_create_form()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->get(route(self::ROUTE_ADMIN_CATEGORY_CREATE));

        $response->assertOk()
            ->assertViewIs(self::ROUTE_ADMIN_CATEGORY_CREATE);
    }

    public function test_it_creates_new_category()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $categoryData = [
            'name' => 'Test Category',
            'sort' => 1,
        ];

        $response = $this->actingAs($user)
            ->post(route(self::ROUTE_ADMIN_CATEGORY_STORE), $categoryData);

        $response->assertRedirect(route(self::ROUTE_ADMIN_CATEGORY_INDEX));
        $this->assertDatabaseHas('categories', $categoryData);
    }

    public function test_it_validates_required_fields()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->from(route(self::ROUTE_ADMIN_CATEGORY_CREATE))
            ->post(route(self::ROUTE_ADMIN_CATEGORY_STORE), []);

        $response->assertRedirect()
            ->assertInvalid(['name']);
    }

    public function test_it_validates_sort_field_type()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->from(route(self::ROUTE_ADMIN_CATEGORY_CREATE))
            ->post(route(self::ROUTE_ADMIN_CATEGORY_STORE), [
                'name' => 'Test Category',
                'sort' => 'not-a-number'
            ]);

        $response->assertRedirect()
            ->assertInvalid(['sort']);
    }

    public function test_it_requires_authentication()
    {
        $response = $this->post(route(self::ROUTE_ADMIN_CATEGORY_STORE), []);

        $response->assertRedirect(route(self::ROUTE_LOGIN));
    }

    public function test_it_requires_admin_role()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->post(route(self::ROUTE_ADMIN_CATEGORY_STORE), []);

        $response->assertForbidden();
    }
}
