<?php

namespace Tests\Feature\Repositories\UserRepository;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use PHPUnit\Framework\Attributes\Group;
use App\Services\Repositories\DTOs\UserProfileDTO;
use App\Infrastructure\Eloquent\Repositories\UserRepository;


#[Group('repository')]
class UserProfileTest extends TestCase
{
    protected UserRepository $repository;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new UserRepository;
    }

    public function test_user_profile_can_update(): void
    {
        $user = User::factory()->create();

        $profile = UserProfile::factory()->make();

        $profileDTO = new UserProfileDTO(
            userId: $user->id,
            biography: $profile->biography,
        );

        $id = $this->repository->saveProfile($profileDTO);

        $this->assertNotNull($id);
    }
}
