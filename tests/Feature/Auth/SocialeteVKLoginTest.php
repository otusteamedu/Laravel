<?php

namespace Tests\Feature\Auth;


use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;

#[Group('login')]
class SocialeteVKLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect(): void
    {
        $config = Config::get('services.vkid');

        if (empty($config['client_id'])) {
            $this->markTestSkipped('Сервис не настроен');
        }

        $response = $this->get(route(name: 'login.vk', absolute: false));
        $response->assertStatus(302);

        $targetUrl = $response->getTargetUrl();

        $queryString = parse_url($targetUrl, PHP_URL_QUERY);

        parse_str($queryString, $queryArray);

        $this->assertEquals($config['client_id'], $queryArray['client_id']);
        $this->assertEquals($config['redirect'], $queryArray['redirect_uri']);

        dump($targetUrl);

        $response = $this->get($targetUrl);

        $response->dump();
        $response->dumpSession();
        $response->dumpHeaders();

        dump($response->status());
    }
}
