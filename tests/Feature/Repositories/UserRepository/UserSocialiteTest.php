<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserSocialite;
use PHPUnit\Framework\Attributes\Group;
use App\Services\Repositories\DTOs\UserSocialiteDTO;
use App\Infrastructure\Eloquent\Repositories\UserSocialiteRepository;

#[Group('repository')]
class UserSocialiteTest extends TestCase
{
    protected userSocialiteRepository $repository;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new UserSocialiteRepository;
    }

    public function test_user_get_by_socialite(): void
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

    public function test_user_socialite_not_find(): void
    {
        $finded = $this->repository->find('', '');

        $this->assertNull($finded);
    }

    public function test_user_socialite_can_added(): void
    {
        $user = User::factory()->create();

        $payload = new UserSocialiteDTO(
            userId: $user->id,
            driver: 'yandex',
            socialiteId: '1234567890'
        );

        $id = $this->repository->add($payload);

        $this->assertNotNull($id);
    }

    public function test_user_socialite_can_updated(): void
    {
        $user = User::factory()->create();

        $socialite = UserSocialite::create([
            'user_id'      => $user->id,
            'driver'       => 'yandex',
            'socialite_id' => '1234567890',
        ]);

        $update = new UserSocialiteDTO(
            userId: $user->id,
            driver: 'vk',
            socialiteId: '0987654321',
            id: $socialite->id
        );

        $success = $this->repository->save($update);
        $this->assertTrue($success);

        $updated = $socialite->refresh();

        $this->assertSame($update->driver, $updated->driver);
        $this->assertSame($update->socialiteId, $updated->socialite_id);
    }

    public function test_user_socialite_can_deleted(): void
    {
        $user = User::factory()->create();

        $socialite = UserSocialite::create([
            'user_id'      => $user->id,
            'driver'       => 'yandex',
            'socialite_id' => '1234567890',
        ]);

        $success = $this->repository->destroy($socialite->id);

        $this->assertTrue($success);
    }
}
