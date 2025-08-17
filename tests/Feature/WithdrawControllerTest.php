<?php

namespace Tests\Feature;

use App\Services\WithdrawService\WithdrawService;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('withdraw')]
class WithdrawControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_ok_withdraw(): void
    {
        $this->mock(WithdrawService::class, function (MockInterface $withdrawServiceMock) {
            $withdrawServiceMock->expects('withdraw')->with(1, 100)->andReturn(777);
        });

        $response = $this->get(route('withdraw'));
        dd($response->assertJson(['ok' => true]));
        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);
    }
}
