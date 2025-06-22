<?php

namespace Tests\Feature\Admin\Users;

use Tests\Feature\Admin\AdminTestCase;
use App\Models\User;

class UserDeleteTest extends AdminTestCase
{
    public function test_admin_can_delete_user()
    {
        $userToDelete = User::factory()->create(['name' => 'Для удаления']);

        $response = $this->asAdmin()
            ->delete(route('admin.users.destroy', $userToDelete));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
        $response->assertSessionHas('success');
    }

    public function test_returns_404_for_nonexistent_user()
    {
        $response = $this->asAdmin()
            ->delete(route('admin.users.destroy', 999));

        $response->assertStatus(404);
    }

    public function test_unauthorized_user_redirected_to_login()
    {
        $user = User::factory()->create();
        $this->assertGuestRedirectedToLogin('admin.users.destroy', 'delete', [], ['user' => $user->id]);
    }
} 