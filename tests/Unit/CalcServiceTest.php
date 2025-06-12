<?php

namespace Tests\Unit;

use App\Services\CalcService;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

#[Group('calc-service')]
class CalcServiceTest extends TestCase
{
    #[TestWith([25, 5])]
    #[TestWith([81, 8])]
    #[TestWith([-36, 6])]
    public function test_sqrt($a, $expected): void
    {
        // Arrange
        $calc = new CalcService;

        // Act
        $result = $calc->sqrt($a);

        // Assert
        $this->assertEquals($expected, $result);
    }
}
