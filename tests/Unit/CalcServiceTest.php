<?php

namespace Tests\Unit;

use App\Services\CalcService;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

#[Group("calc-service")]
class CalcServiceTest extends TestCase
{
    #[TestWith([0, 0, 0])]
    #[TestWith([1, 3, 4])]
    #[TestWith([1, 8, 9])]
    public function test_sum($a, $b, $expected): void
    {
        // Arrange
        $calc = new CalcService();

        // Act
        $result = $calc->sum($a, $b);

        // Assert
        $this->assertEquals($expected, $result);
    }

    public function test_sub(): void
    {
        // Arrange
        $calc = new CalcService();
        $a = 1;
        $b = 3;
        $expected = -2;

        // Act
        $result = $calc->sub($a, $b);

        // Assert
        $this->assertEquals($expected, $result);
    }
}
