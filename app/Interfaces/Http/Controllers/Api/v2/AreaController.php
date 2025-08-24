<?php

namespace App\Interfaces\Http\Controllers\Api\v2;

use App\Infrastructure\Helpers\LocaleHelper;
use App\Interfaces\Http\Requests\Area\StoreAreaRequest;
use App\Interfaces\Http\Requests\Area\UpdateAreaRequest;
use App\Interfaces\Response\WebResponse;
use App\Application\Services\Area\AreaServiceInterface;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * @OA\Tag(
 *     name="Area",
 *     description="Управление территориями"
 * )
 */
class AreaController extends Controller
{
    public AreaServiceInterface $areaService;

    public function __construct(AreaServiceInterface $areaService)
    {
        $this->areaService = $areaService;
    }

    /**
     * @OA\Get(
     *     path="/api/v2/area",
     *     tags={"Area"},
     *     summary="Список территорий",
     *     security={{"passport":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $areas = $this->areaService->prepairDataForIndex();
            $response = new WebResponse(true, $areas, 'Успешно');
        } catch (Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json(
                $response->toArray(),
                $response->statusCode,
                [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'JSON_UNESCAPED_UNICODE' => true
                ],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v2/areas",
     *     tags={"Areas"},
     *     summary="Создание новой области",
     *     description="Создает новую область и возвращает данные добавленной записи",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name-area", type="string", example="Центральный регион")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Запись добавлена",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Центральный регион")
     *                 )
     *             ),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Запись добавлена")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Ошибка запроса",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ошибка валидации"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 additionalProperties=@OA\Property(type="string")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(StoreAreaRequest $request): JsonResponse
    {
        try {
            $this->areaService->store($request->input('name-area'), LocaleHelper::getLocale());
            $response = new WebResponse(true, [], 'Запись добавлена', [], 201);
        } catch (Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json(
                $response->toArray(),
                $response->statusCode,
                [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'JSON_UNESCAPED_UNICODE' => true
                ],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v2/area/{id}",
     *     tags={"Area"},
     *     summary="Просмотр одной территории",
     *     security={{"passport":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {
            $area = $this->areaService->prepairDataForEdit($id);
            $response = new WebResponse(true, $area, 'Успешно');
        } catch (Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json(
                $response->toArray(),
                $response->statusCode,
                [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'JSON_UNESCAPED_UNICODE' => true
                ],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v2/areas/{id}",
     *     tags={"Areas"},
     *     summary="Обновление области",
     *     description="Обновляет данные области по ID и возвращает обновленную запись",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID области",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name-area", type="string", example="Обновленный регион")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Обновлено успешно",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Обновленный регион")
     *                 )
     *             ),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Запись обновлена")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Ошибка запроса",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ошибка валидации"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 additionalProperties=@OA\Property(type="string")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(UpdateAreaRequest $request, int $id): JsonResponse
    {
        try {
            $this->areaService->update($id, $request->input('name-area'));
            $response = new WebResponse(true, [], 'Запись успешно сохранена', [], 201);
        } catch (Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json(
                $response->toArray(),
                $response->statusCode,
                [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'JSON_UNESCAPED_UNICODE' => true
                ],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/areas/{id}",
     *     tags={"Areas"},
     *     summary="Удаление области",
     *     description="Удаляет область по ID и возвращает результат операции",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID области",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Удалено успешно",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Название удаленной области")
     *                 )
     *             ),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Область удалена")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Ошибка запроса",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ошибка при удалении"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 additionalProperties=@OA\Property(type="string")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->areaService->delete($id);
            $response = new WebResponse(true, [], 'Запись успешно удалена', [], 201);
        } catch (Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json(
                $response->toArray(),
                $response->statusCode,
                [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'JSON_UNESCAPED_UNICODE' => true
                ],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
