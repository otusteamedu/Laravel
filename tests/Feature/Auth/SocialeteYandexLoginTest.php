<?php

namespace Tests\Feature\Auth;


use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;

#[Group('login')]
class SocialeteYandexLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect(): void
    {
        $config = Config::get('services.yandex');

        if (empty($config['client_id'])) {
            $this->markTestSkipped('Сервис не настроен');
        }

        $response = $this->get(route(name: 'login.yandex', absolute: false));
        $response->assertStatus(302);

        $queryString = parse_url($response->getTargetUrl(), PHP_URL_QUERY);

        parse_str($queryString, $queryArray);

        $this->assertEquals($config['client_id'], $queryArray['client_id']);
        $this->assertEquals($config['redirect'], $queryArray['redirect_uri']);
    }
}
