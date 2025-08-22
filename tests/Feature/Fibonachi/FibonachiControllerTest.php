<?php

namespace Tests\Feature\Fibonachi;

use App\Infrastructure\EloquentModels\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

#[Group('feature_fibonachi')]

class FibonachiControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected string $indexUrl;
    protected string $calculateUrl;

    public function setUp(): void
    {
        $this->setUpTheTestEnvironment();
        $this->indexUrl = 'fibonachi.index';
        $this->calculateUrl = 'fibonachi.calculate';
    }

    #[Test()]
    public function index_view_loads_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route($this->indexUrl));

        $response->assertStatus(200);
        $response->assertViewIs('fibonachi.index.index');
        $response->assertViewHas('response');
    }

    #[Test()]
    #[TestWith([5, 200, true, [0,1,1,2,3,5]])]
    #[TestWith([5, 403, false, null, false])]
    #[TestWith([0, 422, false, null, true])]
    public function calculate_fibonacci_cases(
        int $number,
        int $expectedStatus,
        bool $expectedSuccess,
        ?array $expectedData = null,
        bool $allowCalculate = true
    ) {
        $user = User::factory()->create();
        $this->actingAs($user);

        Gate::shouldReceive('allows')
            ->once()
            ->with('calculate', User::class)
            ->andReturn($allowCalculate);

        $response = $this->getJson(route($this->calculateUrl, ['number' => $number]));

        $response->assertStatus($expectedStatus);
        $response->assertJson([
            'success' => $expectedSuccess,
            'data' => $expectedData,
        ]);
    }
}
