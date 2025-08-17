<?php

declare(strict_types=1);

namespace Http\Controllers\Api\v1\QauthController;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_register_successful(): void
    {
        // Arrange
        $userData = [
            'name' => '12345',
            'email' => 'john16@example.com',
            'password' => 'password123',
        ];
        // Act
        $response = $this->postJson('api/v1/auth/register', $userData);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }
}
