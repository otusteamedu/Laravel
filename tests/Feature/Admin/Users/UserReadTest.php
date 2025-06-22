<?php
namespace Tests\Feature\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserReadTest extends TestCase {
    use RefreshDatabase;
    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_read_users()
    {
        // Создаем несколько пользователей
        $users = User::factory()->count(3)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);

        // Проверяем что все пользователи отображаются
        foreach ($users as $user) {
            $response->assertSee($user->name);
        }
    }

    public function test_pagination_works_in_users_list()
    {
        // Создаем 15 пользователей
        User::factory()->count(15)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);

        // Проверяем что передана переменная с пагинацией
        $response->assertViewHas('users');

        // Или проверяем наличие пагинации в HTML
        $response->assertSee('pagination');
    }
}
