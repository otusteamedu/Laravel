<?php

namespace Tests\Feature\Admin;

use App\Domain\Apartment\Apartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаём и логиним админа
        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($this->admin);
    }

    public function test_index_displays_apartments()
    {
        $apartments = Apartment::factory()->count(3)->create();

        $response = $this->get(route('admin.apartments.index'));

        $response->assertStatus(200);

        foreach ($apartments as $apartment) {
            $response->assertSee(e($apartment->owner));
            $response->assertSee(e($apartment->serial_number));
        }
    }

    public function test_create_displays_form()
    {
        $response = $this->get(route('admin.apartments.create'));

        $response->assertStatus(200);
        $response->assertSee('form'); // проверяем, что есть форма
    }

    public function test_store_saves_and_redirects()
    {
        $data = [
            'owner' => 'Иван Иванов',
            'serial_number' => '123456',
        ];

        $response = $this->post(route('admin.apartments.store'), $data);

        $response->assertRedirect(route('admin.apartments.index'));
        $this->assertDatabaseHas('apartments', $data);
        $response->assertSessionHas('success', 'Квартира добавлена');
    }

    public function test_edit_displays_form_with_apartment()
    {
        $apartment = Apartment::factory()->create();

        $response = $this->get(route('admin.apartments.edit', $apartment));

        $response->assertStatus(200);
        $response->assertSee(e($apartment->owner));
        $response->assertSee(e($apartment->serial_number));
    }

    public function test_update_changes_data_and_redirects()
    {
        $apartment = Apartment::factory()->create();

        $data = [
            'owner' => 'Пётр Петров',
            'serial_number' => '654321',
        ];

        $response = $this->put(route('admin.apartments.update', $apartment), $data);

        $response->assertRedirect(route('admin.apartments.index'));
        $this->assertDatabaseHas('apartments', array_merge(['id' => $apartment->id], $data));
        $response->assertSessionHas('success', 'Квартира обновлена');
    }
}
