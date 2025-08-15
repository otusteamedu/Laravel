<?php
namespace Tests\Unit\Policies;

use App\Models\Permission;
use App\Models\Product;
use App\Models\User;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('product_policy')]
class ProductPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
    protected User $editor;
    protected Product $adminProduct;
    protected Product $editorProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            Permission::firstOrCreate(['name' => 'view-product']),
            Permission::firstOrCreate(['name' => 'create-product']),
            Permission::firstOrCreate(['name' => 'edit-product']),
            Permission::firstOrCreate(['name' => 'delete-product']),
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
        $this->adminProduct = Product::factory()->create(['user_id' => $this->admin->id]);
        $this->editorProduct = Product::factory()->create(['user_id' => $this->editor->id]);
    }

    #[Test]
    public function view_any_returns_true_for_all_users()
    {
        $policy = new ProductPolicy();

        $this->assertTrue($policy->viewAny($this->admin));
        $this->assertFalse($policy->viewAny($this->user));
    }

    #[Test]
    public function view_returns_true_for_all_users()
    {
        $policy = new ProductPolicy();

        $this->assertTrue($policy->view($this->admin, $this->adminProduct));
        $this->assertFalse($policy->view($this->user, $this->adminProduct));
    }

    #[Test]
    public function create_checks_permission()
    {
        $policy = new ProductPolicy();

        $this->assertTrue($policy->create($this->admin));
        $this->assertFalse($policy->create($this->user));
    }

    #[Test]
    public function update_checks_permission_and_ownership()
    {
        $policy = new ProductPolicy();

        // Admin can update their own product
        $this->assertTrue($policy->update($this->admin, $this->adminProduct));
        $this->assertTrue($policy->update($this->editor, $this->editorProduct));

        $this->assertFalse($policy->update($this->editor, $this->adminProduct));

        // User without permission cannot update
        $this->assertFalse($policy->update($this->user, $this->editorProduct));

        // Even admin cannot update someone else's product
        //$otherProduct = Product::factory()->create(['user_id' => $this->user->id]);
        //$this->assertFalse($policy->update($this->admin, $otherProduct));
    }

    #[Test]
    public function delete_checks_permission_and_ownership()
    {
        $policy = new ProductPolicy();

        // Admin can delete their own product
        $this->assertTrue($policy->delete($this->admin, $this->adminProduct));
        $this->assertTrue($policy->delete($this->editor, $this->editorProduct));

        $this->assertFalse($policy->delete($this->editor, $this->adminProduct));

        // User without permission cannot delete
        $this->assertFalse($policy->delete($this->user, $this->editorProduct));

        // Even admin cannot delete someone else's product
        //$otherProduct = Product::factory()->create(['user_id' => $this->user->id]);
        //$this->assertFalse($policy->delete($this->admin, $otherProduct));
    }
}
