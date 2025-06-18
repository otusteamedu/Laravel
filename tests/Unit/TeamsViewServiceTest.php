<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Repositories\TeamRepository;
use App\Services\Team\TeamData;
use App\Services\Team\TeamsViewService;
use Illuminate\Database\Eloquent\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class TeamsViewServiceTest extends TestCase
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

    public function testFetchOneReturnsNull()
    {
        $this->mock(TeamRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('one')
                ->with($this->teamData->id)
                ->once()
                ->andReturn(null);
        });

        $service = app(TeamsViewService::class);
        $service->fetchOne($this->teamData->id);
    }

    public function testFetchOneReturnsTeam()
    {

        $team = Team::factory()->make();
        $this->mock(TeamRepository::class, function (MockInterface $mock) use ($team) {
            $mock->shouldReceive('one')
                ->with($this->teamData->id)
                ->once()
                ->andReturn($team);
        });

        $service = app(TeamsViewService::class);
        $teamData = $service->fetchOne($this->teamData->id);
        $this->assertEquals(new TeamData($team->toArray()), $teamData);
    }

    public function testFetchAllReturnsEmptyArray()
    {
        $this->mock(TeamRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('all')
                ->once()
                ->andReturn(new Collection());
        });

        $service = app(TeamsViewService::class);
        $result = $service->fetchAll();
        $this->assertEquals([], $result);
    }

    public function testFetchAllReturnsTeams()
    {
        $teams = Team::factory(5)->make()->each(function ($team, $key) {
            $team->id = $key + 1;
        });

        $this->mock(TeamRepository::class, function (MockInterface $mock) use ($teams) {
            $mock->shouldReceive('all')
                ->once()
                ->andReturn($teams);
        });

        $service = app(TeamsViewService::class);
        $result = $service->fetchAll();
        $this->assertIsArray($result);
        $this->assertCount(5, $result);
        $this->assertEquals(3, $result[3]->id);
    }
}
