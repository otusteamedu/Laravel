<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SecurityScheme
{
    // Класс-заглушка для swagger-php
}
