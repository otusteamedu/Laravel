<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Tasks API",
 *     version="1.0.0",
 *     description="API для управления задачами с OAuth2 аутентификацией"
 * )
 * 
 * @OA\Server(
 *     url="/api/v1",
 *     description="Tasks API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */
abstract class Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use \Illuminate\Foundation\Validation\ValidatesRequests;
}