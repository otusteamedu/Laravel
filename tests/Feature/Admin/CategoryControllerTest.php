<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('category')]
class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTheTestEnvironment();
    }

    public function test_category_list()
    {
        $user = User::factory()->create(['role_id' => 1]);
        Category::factory()->create(['title' => 'B Category']);
        Category::factory()->create(['title' => 'A Category']);
        Category::factory()->create(['title' => 'C Category']);

        $url = route('admin.categories.index') . '?sort=title&direction=asc';
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.categories.index');
        $response->assertViewHas('categories', function ($categories) {
            $condition1 = $categories[0]->getTitle() === 'A Category';
            $condition2 = $categories[1]->getTitle() === 'B Category';
            $condition3 = $categories[2]->getTitle() === 'C Category';
            return $condition1 && $condition2 && $condition3;
        });
        $response->assertSee('A Category');
        $response->assertSee('B Category');
        $response->assertSee('C Category');
    }

    public function test_category_show()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create([
            'title' => 'Test Category',
            'description' => 'Test Description'
        ]);

        $url = route('admin.categories.show', $category->getId());
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.categories.show');
        $response->assertViewHas('categoryId', $category->getId());
        $response->assertViewHas('title', 'Test Category');
        $response->assertViewHas('description', 'Test Description');
        $response->assertViewHas('createdAt');
        $response->assertSee('Test Category');
        $response->assertSee('Test Description');

        $url = route('admin.categories.show', 100);
        $response = $this->actingAs($user)->get($url);

        $response->assertNotFound();
    }

    public function test_category_create()
    {
        $user = User::factory()->create(['role_id' => 1]);

        $url = route('admin.categories.create');
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.categories.create');
        $response->assertSee('Название категории');
        $response->assertSee('Описание категории');
    }

    public function test_category_store()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $data = [
            'title' => 'Test Category',
            'description' => 'Test Description'
        ];

        $url = route('admin.categories.store');
        $response = $this->actingAs($user)->post($url, $data);

        $redirectUrl = route('admin.categories.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_category_edit()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create([
            'title' => 'Test Category',
            'description' => 'Test Description'
        ]);

        $url = route('admin.categories.edit', $category->getId());
        $response = $this->actingAs($user)->get($url);

        $response->assertOk();
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas('categoryId', $category->getId());
        $response->assertViewHas('title', 'Test Category');
        $response->assertViewHas('description', 'Test Description');
        $response->assertSee('Test Category');
        $response->assertSee('Test Description');

        $url = route('admin.categories.edit', 100);
        $response = $this->actingAs($user)->get($url);

        $response->assertNotFound();
    }

    public function test_category_update()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create();
        $newData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description'
        ];

        $url = route('admin.categories.update', $category->getId());
        $response = $this->actingAs($user)->put($url, $newData);

        $redirectUrl = route('admin.categories.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseHas('categories', array_merge(['id' => $category->getId()], $newData));

        $url = route('admin.categories.update', 100);
        $response = $this->actingAs($user)->put($url, $newData);

        $response->assertNotFound();
    }

    public function test_category_destroy()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $category = Category::factory()->create();

        $url = route('admin.categories.destroy', $category->getId());
        $response = $this->actingAs($user)->delete($url);

        $redirectUrl = route('admin.categories.index');
        $response->assertRedirect($redirectUrl);
        $this->assertDatabaseMissing('categories', ['id' => $category->getId()]);

        $url = route('admin.categories.destroy', 100);
        $response = $this->actingAs($user)->delete($url);

        $response->assertNotFound();
    }
}
