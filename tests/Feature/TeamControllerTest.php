<?php

namespace Tests\Feature;

use App\Http\Controllers\TeamController;
use App\Models\User;
use App\Services\Team\TeamData;
use App\Services\Team\TeamDestroyService;
use App\Services\Team\TeamNotFoundException;
use App\Services\Team\TeamsViewService;
use App\Services\Team\TeamUpdateService;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->make();
        $this->user->role_id = 2;
        $this->actingAs($this->user);

        $this->teamData = new TeamData([
            'id' => fake()->numberBetween(1, 100),
            'nickname' => fake()->name,
            'name' => fake()->name,
            'logo_path' => fake()->word(),
        ]);
    }

    /**
     * @see TeamController::index()
     */
    public function testIndex()
    {
        $response = $this->get(route('teams.index'));
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertSeeText('Команды');
    }

    /**
     * @see TeamController::create()
     */
    public function testCreate()
    {
        $response = $this->get(route('teams.create'));
        $response->assertStatus(200);
        $response->assertSeeText('Создание команды');
    }

    /**
     * @see TeamController::store()
     */
    public function testStoreSuccess()
    {
        $response = $this->post(route('teams.store'), $this->teamData->toArray());
        $response->assertRedirect(route('teams.index'));
    }

    /**
     * @see TeamController::store()
     */
    public function testStoreFailureValidation()
    {
        $response = $this->post(route('teams.store'));
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['nickname', 'name']);
    }

    /**
     * @see TeamController::show()
     */
    public function testShow()
    {
        $this->mock(TeamsViewService::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchOne')->once()->andReturn($this->teamData);
        });

        $response = $this->get(route('teams.show', ['team' => $this->teamData->id]));
        $response->assertStatus(200);
        $response->assertSeeText($this->teamData->name);
    }

    /**
     * @see TeamController::show()
     */
    public function testShowNotFound()
    {
        $this->mock(TeamsViewService::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchOne')->once()->andReturn(null);
        });

        $response = $this->get(route('teams.show', ['team' => $this->teamData->id]));
        $response->assertStatus(404);
    }

    /**
     * @see TeamController::edit()
     */
    public function testEdit()
    {
        $this->mock(TeamsViewService::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchOne')->once()->andReturn($this->teamData);
        });

        $response = $this->get(route('teams.edit', ['team' => $this->teamData->id]));
        $response->assertStatus(200);
        $response->assertSeeText($this->teamData->name);
    }

    /**
     * @see TeamController::edit()
     */
    public function testEditNotFound()
    {
        $this->mock(TeamsViewService::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchOne')->once()->andReturn(null);
        });

        $response = $this->get(route('teams.edit', ['team' => $this->teamData->id]));
        $response->assertStatus(404);
    }

    /**
     * @see TeamController::edit()
     */
    public function testEditUnauthorized()
    {
        $user = $this->user;
        $user->role_id = 1;
        $this->actingAs($user);

        $response = $this->get(route('teams.edit', ['team' => $this->teamData->id]));
        $response->assertStatus(403);
    }

    /**
     * @see TeamController::update()
     */
    public function testUpdate()
    {
        Storage::fake();
        $this->mock(TeamUpdateService::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->once()->andReturn($this->teamData->logo_path);
        });

        $response = $this->put(route('teams.update', ['team' => $this->teamData->id]), $this->teamData->toArray());
        $response->assertRedirect(route('teams.index'));
    }

    /**
     * @see TeamController::update()
     */
    public function testUpdateFailureValidation()
    {
        $response = $this->put(route('teams.update', ['team' => $this->teamData->id]));
        $response->assertStatus(302)->assertSessionHasErrors();
    }

    /**
     * @see TeamController::destroy()
     */
    public function testDestroy()
    {
        $this->mock(TeamDestroyService::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->once();
        });

        $response = $this->delete(route('teams.destroy', ['team' => $this->teamData->id]));
        $response->assertRedirect(route('teams.index'));
    }

    /**
     * @see TeamController::destroy()
     */
    public function testDestroyThrowsException()
    {
        $this->mock(TeamDestroyService::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->once()->andThrows(TeamNotFoundException::class);
        });

        $response = $this->delete(route('teams.destroy', ['team' => $this->teamData->id]));
        $response->assertRedirect(route('teams.index'))->assertSessionHasErrors();
    }
}
