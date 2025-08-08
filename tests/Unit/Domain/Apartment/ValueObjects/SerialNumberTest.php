<?php

namespace Tests\Unit\Domain\Apartment\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\Apartment\ValueObjects\SerialNumber;

class SerialNumberTest extends TestCase
{
    public function test_can_create_with_positive_number()
    {
        $number = 123;
        $serial = new SerialNumber($number);

        $this->assertSame($number, $serial->toInt());
        $this->assertSame((string)$number, (string)$serial);
    }

    public function test_zero_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Serial number must be positive.');

        new SerialNumber(0);
    }

    public function test_negative_number_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Serial number must be positive.');

        new SerialNumber(-10);
    }
}
