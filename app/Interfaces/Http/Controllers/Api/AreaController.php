<?php

namespace App\Interfaces\Http\Controllers\Api;

use App\Infrastructure\Helpers\LocaleHelper;
use App\Interfaces\Http\Requests\Area\StoreAreaRequest;
use App\Interfaces\Http\Requests\Area\UpdateAreaRequest;
use App\Interfaces\Response\WebResponse;
use App\Application\Services\Area\AreaServiceInterface;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class AreaController extends Controller
{
    public AreaServiceInterface $areaService;

    public function __construct(AreaServiceInterface $areaService)
    {
        $this->areaService = $areaService;
    }

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

    public function create(): JsonResponse
    {
        try {
            $response = new WebResponse(true, [], 'Успешно');
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

    public function edit(int $id): JsonResponse
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
