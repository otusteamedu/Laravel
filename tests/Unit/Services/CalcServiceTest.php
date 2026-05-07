<?php

namespace Tests\Unit\Services;

use App\Services\CalcService;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[Group("calc-service")]
class CalcServiceTest extends TestCase
{
    #[TestWith([1, 2, 3], "all positive")]
    #[TestWith([-1, 2, 1])]
    #[TestWith([0, 2, 2], "zero + positive")]
    public function test_sum_two_numbers($a, $b, $expected): void
    {
        // 1. Arrange
        $calc = new CalcService();

        // 2. Act
        $result = $calc->sum($a, $b);

        // 3. Assert
        $this->assertEquals($expected, $result);
    }
}
