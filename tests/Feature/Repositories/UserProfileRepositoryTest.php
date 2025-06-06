<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use PHPUnit\Framework\Attributes\Group;
use App\Services\Repositories\DTOs\UserProfileDTO;
use App\Infrastructure\Eloquent\Repositories\UserProfileRepository;


#[Group('repository')]
class UserProfileRepositoryTest extends TestCase
{
    protected UserProfileRepository $repository;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new UserProfileRepository;
    }

    public function test_user_profile_find(): void
    {
        $user = User::factory()->has(UserProfile::factory(), 'profile')->create();

        $finded = $this->repository->find($user->id);

        $this->assertNotNull($finded);
    }

    public function test_user_profile_not_find(): void
    {
        $finded = $this->repository->find(0);

        $this->assertNull($finded);
    }

    public function test_user_profile_can_update(): void
    {
        $user = User::factory()->create();

        $profile = UserProfile::factory()->make();

        $profileDTO = new UserProfileDTO(
            user_id: $user->id,
            biography: $profile->biography,
        );

        $id = $this->repository->save($profileDTO);

        $this->assertNotNull($id);
    }
}
