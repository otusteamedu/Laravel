<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($this->admin);
    }

    public function test_index_displays_settings()
    {
        $settings = Setting::factory()->count(3)->create();

        $response = $this->get(route('admin.settings.index'));

        $response->assertStatus(200);

        foreach ($settings as $setting) {
            $response->assertSee(e($setting->month_name));
            $response->assertSee(e($setting->bill));
        }
    }

    public function test_edit_displays_form_with_setting()
    {
        $setting = Setting::factory()->create();

        $response = $this->get(route('admin.settings.edit', $setting));

        $response->assertStatus(200);
        $response->assertSee(e($setting->month_name));
    }

    public function test_update_validates_and_updates_setting()
    {
        $setting = Setting::factory()->create();

        $data = [
            'month_name'    => 'Июль',
            'month_to_pay'  => 'Июль',
            'month_to_date' => 'Июль',
            'bill'          => '1000',
            'pay_up_to'     => '2025-07-31',
        ];

        $response = $this->put(route('admin.settings.update', $setting), $data);

        $response->assertRedirect(route('admin.settings.index'));
        $this->assertDatabaseHas('settings', array_merge(['id' => $setting->id], $data));
        $response->assertSessionHas('success', 'Настройка обновлена');
    }
}
