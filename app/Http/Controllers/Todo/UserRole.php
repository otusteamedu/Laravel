<?php

namespace App\Http\Controllers\Todo;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\UserRoleRequest;
use App\Models\TodoRoleEnum;
use App\Services\UseCases\Commands\Todo\UserRole\Command;
use App\Services\UseCases\Commands\Todo\UserRole\Handler;
use Illuminate\Http\JsonResponse;

class UserRole extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(int $projectId, int $todoId, UserRoleRequest $request, Handler $handler): JsonResponse
    {
        $data = $request->validated();

        try {
            $result = $handler->handle(new Command(
                userId: $data['userId'],
                projectId: $data['projectId'],
                todoId: $data['todoId'],
                role: TodoRoleEnum::from($data['role'])
            ));

            return new JsonResponse(['success' => $result]);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Не удалось назначить пользователю роль',
                'errors' => [
                    $e->getMessage()
                ]
            ], 400);
        }
    }
}
