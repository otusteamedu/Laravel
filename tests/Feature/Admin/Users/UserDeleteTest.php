<?php
namespace Tests\Feature\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDeleteTest extends TestCase{
    use RefreshDatabase;

    private User $adminUser;
    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_delete_user()
    {
        $testUser = User::factory()->create(['name' => 'Пользователь Для Удаления']);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.users.destroy', $testUser));

        $response->assertRedirect(route('admin.users.index'));

        // Проверяем что категория удалилась из базы
        $this->assertDatabaseMissing('categories', ['id' => $testUser->id]);

        $response->assertSessionHas('success');
    }
}
