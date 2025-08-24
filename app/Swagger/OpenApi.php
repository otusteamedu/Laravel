<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="Otus API",
 *     version="0.0.0",
 *     description="API documentation for project"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class OpenApi
{
    //
}