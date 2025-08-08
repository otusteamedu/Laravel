<?php

namespace Tests\Unit\Domain\Apartment\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\Apartment\ValueObjects\Owner;

class OwnerTest extends TestCase
{
    public function test_can_create_owner_with_valid_name()
    {
        $name = 'Иван Иванов';
        $owner = new Owner($name);

        $this->assertSame($name, $owner->toString());
        $this->assertSame($name, (string)$owner);
    }

    public function test_empty_name_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Не может быть пустым');

        new Owner('');
    }

    public function test_name_with_only_spaces_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Не может быть пустым');

        new Owner('    ');
    }
}
