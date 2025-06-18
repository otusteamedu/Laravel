<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Repositories\TeamRepository;
use App\Services\Team\TeamData;
use App\Services\Team\TeamNotFoundException;
use App\Services\Team\TeamUpdateService;
use Mockery\MockInterface;
use Tests\TestCase;

class TeamUpdateServiceTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->teamData = new TeamData([
            'id' => fake()->numberBetween(1, 100),
            'nickname' => fake()->name,
            'name' => fake()->name,
            'logo_path' => fake()->word(),
        ]);
    }

    public function testHandleSuccess()
    {
        $team = Team::factory()->make();
        $this->mock(TeamRepository::class, function (MockInterface $mock) use ($team) {
            $mock->shouldReceive('one')
                ->with($this->teamData->id)
                ->once()
                ->andReturn($team);
            $mock->shouldReceive('update')
                ->with($team)
                ->once();
        });

        $service = app(TeamUpdateService::class);
        $service->handle($this->teamData);
    }

    public function testHandleFail()
    {
        $this->mock(TeamRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('one')
                ->with($this->teamData->id)
                ->once()
                ->andReturn(null);
        });

        $this->expectException(TeamNotFoundException::class);

        $service = app(TeamUpdateService::class);
        $logoPath = $service->handle($this->teamData);
        $this->assertEquals($this->teamData->logo_path, $logoPath);
    }
}
