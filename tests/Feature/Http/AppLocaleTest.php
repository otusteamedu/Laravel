<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Group;

#[Group('http')]
class AppLocaleTest extends TestCase
{
    public function test_app_locale_dropdown_rendered(): void
    {
        $appLocale = App::getLocale();
        $availableLocales = Config::get('locale.available_locales');
        $current = sprintf('<span class="app-locale">%s</span>', $availableLocales[$appLocale]);

        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee('id="localeDropdown"', false);
        $response->assertSee($current, false);


        foreach ($availableLocales as $locale => $localeName) {
            $response->assertSeeInOrder([route('locale.set', ['locale' => $locale]), $localeName], false);
        }
    }

    public function test_app_locale_can_changed(): void
    {
        $appLocale = App::getLocale();
        $availableLocales = Config::get('locale.available_locales');

        unset($availableLocales[$appLocale]);

        $newLocale = array_rand($availableLocales);

        $response = $this->get(route('home'));
        $response->assertOk();

        $response = $this->get(route('locale.set', ['locale' => $newLocale]));
        $response->assertRedirect(route('home'));

        $current = sprintf('<span class="app-locale">%s</span>', $availableLocales[$newLocale]);

        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee($current, false);

        $this->assertEquals($newLocale, App::getLocale());
    }
}
