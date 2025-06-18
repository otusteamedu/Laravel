<?php

namespace Tests\Unit;

use App\Services\Team\TeamData;
use Tests\TestCase;

class TeamDataTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->data = [
            'nickname' => fake()->name,
            'name' => fake()->name,
        ];
    }

    public function testToArrayWithoutNulls()
    {
        $teamDataArray = $this->data;
        $teamDataArray['id'] = null;
        $teamDataArray['logo_path'] = null;

        $teamData = new TeamData($this->data);
        $this->assertEquals($teamDataArray, $teamData->toArray());
    }

    public function testToArrayWithNulls()
    {
        $data = $this->data;
        $data['id'] = fake()->numberBetween(1, 100);
        $data['logo_path'] = fake()->word();

        $teamData = new TeamData($data);
        $this->assertEquals($data, $teamData->toArray());
    }
}
