<?php

namespace App\Modules\ISS\tests\Unit\issServices;

//use PHPUnit\Framework\TestCase;
use App\Modules\ISS\tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Group;

class EducationMaterialTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Проверка что сервис возвращает правильную структуру данных при скачивании файла клиентом
     */
    #[Group(name: "downloadFile")]
    public function test_download_file_service()
    {
        //Storage::shouldReceive('download')->andReturn(...);
    }

    /**
     * Проверка что сервис возвращает правильную структуру данных при отображении файла
     */
    #[Group(name: "openFile")]
    public function test_open_file_service()
    {
        //Storage::shouldReceive('response')->andReturn(...);
    }
}
