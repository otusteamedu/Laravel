<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserSocialite;
use PHPUnit\Framework\Attributes\Group;
use App\Services\Repositories\DTOs\UserSocialeteDTO;
use App\Infrastructure\Eloquent\Repositories\UserSocialeteRepository;

#[Group('repository')]
class UserSocialeteTest extends TestCase
{
    protected UserSocialeteRepository $repository;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new UserSocialeteRepository;
    }

    public function test_user_get_by_socialete(): void
    {
        $user = User::factory()->create();

        UserSocialite::create([
            'user_id'      => $user->id,
            'driver'       => 'yandex',
            'socialite_id' => '1234567890',
        ]);

        $finded = $this->repository->find('1234567890', 'yandex');

        $this->assertNotNull($finded);
    }

    public function test_user_socialete_not_find(): void
    {
        $finded = $this->repository->find('', '');

        $this->assertNull($finded);
    }

    public function test_user_socialete_can_added(): void
    {
        $user = User::factory()->create();

        $payload = new UserSocialeteDTO(
            userId: $user->id,
            driver: 'yandex',
            socialiteId: '1234567890'
        );

        $id = $this->repository->add($payload);

        $this->assertNotNull($id);
    }

    public function test_user_socialete_can_updated(): void
    {
        $user = User::factory()->create();

        $socialete = UserSocialite::create([
            'user_id'      => $user->id,
            'driver'       => 'yandex',
            'socialite_id' => '1234567890',
        ]);

        $update = new UserSocialeteDTO(
            userId: $user->id,
            driver: 'vk',
            socialiteId: '0987654321',
            id: $socialete->id
        );

        $success = $this->repository->save($update);
        $this->assertTrue($success);

        $updated = $socialete->refresh();

        $this->assertSame($update->driver, $updated->driver);
        $this->assertSame($update->socialiteId, $updated->socialite_id);
    }

    public function test_user_socialete_can_deleted(): void
    {
        $user = User::factory()->create();

        $socialete = UserSocialite::create([
            'user_id'      => $user->id,
            'driver'       => 'yandex',
            'socialite_id' => '1234567890',
        ]);

        $success = $this->repository->destroy($socialete->id);

        $this->assertTrue($success);
    }
}
