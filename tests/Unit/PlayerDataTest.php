<?php

namespace Tests\Unit;

use App\Services\TeamPlayer\PlayerData;
use Tests\TestCase;

class PlayerDataTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->data = [
            'nickname' => fake()->name,
            'name' => fake()->name,
            'position' => fake()->word(),
            'team_id' => fake()->numberBetween(1, 100),
            'price' => fake()->numberBetween(1_000, 10_000),
        ];
    }

    public function testToArrayWithoutNulls()
    {
        $playerDataArray = $this->data;
        $playerDataArray['id'] = null;
        $playerDataArray['avatar_path'] = null;

        $playerData = new PlayerData($this->data);
        $this->assertEquals($playerDataArray, $playerData->toArray());
    }

    public function testToArrayWithNulls()
    {
        $data = $this->data;
        $data['id'] = fake()->numberBetween(1, 100);
        $data['avatar_path'] = fake()->word();

        $playerData = new PlayerData($data);
        $this->assertEquals($data, $playerData->toArray());
    }
}
