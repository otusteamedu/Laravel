<?php

namespace Tests\Unit\Policies;

use App\Models\Category;
use App\Models\Permission;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('categories_policy')]
class CategoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $editor;
    protected User $user;
    protected Category $adminCategory;
    protected Category $editorCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            Permission::firstOrCreate(['name' => 'view-category']),
            Permission::firstOrCreate(['name' => 'create-category']),
            Permission::firstOrCreate(['name' => 'edit-category']),
            Permission::firstOrCreate(['name' => 'delete-category']),
        ];

        // Создаем администратора
        $this->admin = User::factory()->create();
        $adminRole = $this->admin->roles()->create(['name' => 'Administrator']);
        $adminRole->permissions()->sync(collect($permissions)->pluck('id'));

        // Создаем редактора с теми же permissions
        $this->editor = User::factory()->create();
        $editorRole = $this->editor->roles()->create(['name' => 'Editor']);
        $editorRole->permissions()->sync(collect($permissions)->pluck('id'));


        $this->user = User::factory()->create();
        $this->adminCategory = Category::factory()->create(['user_id' => $this->admin->id]);
        $this->editorCategory = Category::factory()->create(['user_id' => $this->editor->id]);
    }

    #[Test]
    public function view_any_returns_true_for_all_users()
    {
        $policy = new CategoryPolicy();

        $this->assertTrue($policy->viewAny($this->admin));
        $this->assertFalse($policy->viewAny($this->user));
    }

    #[Test]
    public function view_returns_true_for_all_users()
    {
        $policy = new CategoryPolicy();

        $this->assertTrue($policy->view($this->admin, $this->editorCategory));
        $this->assertFalse($policy->view($this->user, $this->editorCategory));
    }

    #[Test]
    public function create_checks_permission()
    {
        $policy = new CategoryPolicy();

        $this->assertTrue($policy->create($this->admin));
        $this->assertFalse($policy->create($this->user));
    }

    #[Test]
    public function update_checks_permission_and_ownership()
    {
        $policy = new CategoryPolicy();

        // Admin can update their own category
        $this->assertTrue($policy->update($this->admin, $this->adminCategory));
        $this->assertTrue($policy->update($this->editor, $this->editorCategory));

        $this->assertFalse($policy->update($this->editor, $this->adminCategory));

        // User without permission cannot update
        $this->assertFalse($policy->update($this->user, $this->editorCategory));

        // Admin can update someone else's category
        //$otherCategory = Category::factory()->create(['user_id' => $this->user->id]);
        //$this->assertTrue($policy->update($this->admin, $otherCategory));
    }

    #[Test]
    public function delete_checks_permission_and_ownership()
    {
        $policy = new CategoryPolicy();

        // Admin can delete their own category
        $this->assertTrue($policy->delete($this->admin, $this->adminCategory));
        $this->assertTrue($policy->delete($this->editor, $this->editorCategory));

        $this->assertFalse($policy->delete($this->editor, $this->adminCategory));

        // User without permission cannot delete
        $this->assertFalse($policy->delete($this->user, $this->editorCategory));

        // Admin can delete someone else's category
        //$otherCategory = Category::factory()->create(['user_id' => $this->user->id]);
        //$this->assertTrue($policy->delete($this->admin, $otherCategory));
    }
}
