<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Group;
use App\Services\Repositories\DTOs\UserDTO;
use App\Services\Repositories\DTOs\UserCreateDTO;
use App\Infrastructure\Eloquent\Repositories\UserRepository;


#[Group('repository')]
class UserTest extends TestCase
{
    protected UserRepository $repository;

    protected function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->repository = new UserRepository;
    }

    public function test_user_can_added(): void
    {
        $user = User::factory()->make();

        $payload = new UserCreateDTO(
            name: $user->name,
            email: $user->email,
            password: Hash::make(Str::random(10)),
        );

        $userId = $this->repository->add($payload);

        $userDTO = $this->repository->find($userId);

        $this->assertNotNull($userDTO);
    }

    public function test_user_can_updated(): void
    {
        $user = User::factory()->create();

        $newUser = User::factory()->make();

        $update = new UserDTO(
            userId: $user->id,
            name: $newUser->name,
            email: $newUser->email,
        );

        $success = $this->repository->save($update);

        $this->assertTrue($success);

        $updated = $user->refresh();

        $this->assertSame($update->name, $updated->name);
        $this->assertSame($update->email, $updated->email);
    }

    public function test_find_user_by_email(): void
    {
        $user = User::factory()->create();

        $finded = $this->repository->findByEmail($user->email);

        $this->assertNotNull($finded);
    }

    public function test_find_user_by_id_not_found(): void
    {
        $finded = $this->repository->find(0);

        $this->assertNull($finded);
    }

    public function test_find_user_by_email_not_found(): void
    {
        $finded = $this->repository->findByEmail('@');

        $this->assertNull($finded);
    }
}
