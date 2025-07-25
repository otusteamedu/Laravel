<?php

namespace App\Http\API\V1\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\API\V1\Responses\ErrorResponse;
use App\Http\API\V1\Responses\SuccessResponse;
use App\Http\API\V1\Requests\Project\StoreRequest;
use App\Services\UseCases\Commands\Project\Create\Command;
use App\Services\UseCases\Commands\Project\Create\Handler;

class ProjectController extends Controller
{
    /**
     * Создать проект
     * @param \App\Http\API\V1\Requests\Project\StoreRequest $request
     * @param \App\Services\UseCases\Commands\Project\Create\Handler $handler
     * @return JsonResponse
     */
    public function store(StoreRequest $request, Handler $handler)
    {
        $data = $request->validated();

        try {
            $result = $handler->handle(
                new Command(
                    name: $data['name'],
                    description: $data['description'],
                    userId: $data['user_id'],
                )
            );

            return new JsonResponse(
                new SuccessResponse(
                    payload: $result,
                    success: true,
                    code: 200
                )
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                new ErrorResponse(
                    message: $exception->getMessage(),
                    errors: [],
                    code: 422,
                )
            );
        }
    }
}
