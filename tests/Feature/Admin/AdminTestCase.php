<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Model;

abstract class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['is_admin' => true]);
        $this->regularUser = User::factory()->create(['is_admin' => false]);
    }

    /**
     * Хелпер для админ запросов
     */
    protected function asAdmin()
    {
        return $this->actingAs($this->adminUser);
    }

    /**
     * Хелпер для запросов обычного пользователя
     */
    protected function asRegularUser()
    {
        return $this->actingAs($this->regularUser);
    }

    /**
     * Тест что неавторизованный пользователь перенаправляется на логин
     */
    protected function assertGuestRedirectedToLogin(string $route, string $method = 'get', array $data = [], array $params = []): void
    {
        $url = empty($params) ? route($route) : route($route, $params);

        $response = match(strtolower($method)) {
            'post' => $this->post($url, $data),
            'put' => $this->put($url, $data),
            'delete' => $this->delete($url, $data),
            default => $this->get($url)
        };

        $response->assertRedirect(route('login'));
    }

    /**
     * Тест, что обычный пользователь получает 403
     */
    protected function assertRegularUserGetsForbidden(string $route): void
    {
        $response = $this->asRegularUser()->get($route);
        $response->assertStatus(403);
    }

    /**
     * Тест 404 для несуществующего ресурса
     */
    protected function assert404ForNonexistentResource(string $route, array $params = []): void
    {
        $url = route($route, $params);
        $response = $this->asAdmin()->get($url);
        $response->assertStatus(404);
    }

    /**
     * Тест чтения списка ресурсов
     */
    protected function assertCanReadResourcesList(string $route, $items, string $nameField = 'name'): void
    {
        $response = $this->asAdmin()->get($route);
        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->{$nameField});
        }
    }

    /**
     * Тест пагинации
     */
    protected function assertPaginationWorks(string $route, string $viewVariable, int $itemsCount = 15): void
    {
        $response = $this->asAdmin()->get($route);
        $response->assertStatus(200);
        $response->assertViewHas($viewVariable);
        $response->assertSee('pagination');
    }

    /**
     * Тест успешного создания ресурса
     */
    protected function assertSuccessfulCreation(
        string $redirectRoute,
        array $dbData,
        string $table,
        string $successMessage
    ): void {
        $response = $this->get($redirectRoute);
        $response->assertRedirect($redirectRoute);
        $this->assertDatabaseHas($table, $dbData);
        $response->assertSessionHas('success', $successMessage);
    }

    /**
     * Тест успешного обновления ресурса
     */
    protected function assertSuccessfulUpdate(
        string $redirectRoute,
        array $dbData,
        string $table
    ): void {
        $response = $this->get($redirectRoute);
        $response->assertRedirect($redirectRoute);
        $this->assertDatabaseHas($table, $dbData);
        $response->assertSessionHas('success');
    }

    /**
     * Тест успешного удаления ресурса
     */
    protected function assertSuccessfulDeletion(
        string $redirectRoute,
        int $resourceId,
        string $table
    ): void {
        $response = $this->get($redirectRoute);
        $response->assertRedirect($redirectRoute);
        $this->assertDatabaseMissing($table, ['id' => $resourceId]);
        $response->assertSessionHas('success');
    }

    /**
     * Тест валидационных ошибок
     */
    protected function assertValidationErrors(array $fields): void
    {
        foreach ($fields as $field) {
            $response = $this->get(request()->url());
            $response->assertSessionHasErrors($field);
        }
    }
}
