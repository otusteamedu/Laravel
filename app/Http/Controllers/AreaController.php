<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\Area\StoreAreaRequest;
use App\Http\Requests\Area\UpdateAreaRequest;
use App\Response\WebResponse;
use App\Services\Area\AreaServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class AreaController extends Controller
{
    public AreaServiceInterface $areaService;

    public function __construct(AreaServiceInterface $areaService)
    {
        $this->areaService = $areaService;
    }

    public function index(): Response
    {
        try {
            $areas = $this->areaService->prepairDataForIndex();
            $response = new WebResponse(true, $areas, 'Успешно');
        } catch (NotFoundException $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], $e->getCode());
            Log::warning(__METHOD__ . var_export($response, true));
        } catch (Throwable $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 500);
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->view('area.index.index', compact('response'), $response->statusCode);
        }
    }

    public function create(): Response
    {
        try {
            $response = new WebResponse(true, [], 'Успешно');
        } catch (Throwable $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 500);
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->view('area.create.create', compact('response'), $response->statusCode);
        }
    }

    public function store(StoreAreaRequest $request): JsonResponse
    {
        try {
            $this->areaService->store($request->input('name-area'));
            $response = new WebResponse(true, [], 'Запись добавлена', [], 201);
        } catch (QueryException $e) {
            $response = new WebResponse(false, null, 'Запись не добавлена', [], 500);
            Log::error(__METHOD__ . var_export($e->getMessage(), true));
        } catch (Throwable $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 500);
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json($response->toArray(), $response->statusCode);
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(int $id)
    {
        try {
            $area = $this->areaService->prepairDataForEdit($id);
            $response = new WebResponse(true, $area, 'Успешно');
        } catch (ModelNotFoundException $e) {
            $response = new WebResponse(false, null, 'Запись не найдена для редактирования', [], 400);
            Log::error(__METHOD__ . var_export($response, true));
        } catch (Throwable $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 500);
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->view('area.edit.edit', compact('response'), $response->statusCode);
        }
    }

    public function update(UpdateAreaRequest $request, int $id)
    {
        try {
            $this->areaService->update($id, $request->input('name-area'));
            $response = new WebResponse(true, [], 'Запись успешно сохранена', [], 201);
        } catch (ModelNotFoundException $e) {
            $response = new WebResponse(false, null, 'Запись не найдена для редактирования', [], 400);
            Log::error(__METHOD__ . var_export($e->getMessage(), true));
        } catch (QueryException $e) {
            $response = new WebResponse(false, null, 'Запись не сохранена', [], 500);
            Log::error(__METHOD__ . var_export($e->getMessage(), true));
        } catch (Throwable $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 500);
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json($response->toArray(), $response->statusCode);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->areaService->delete($id);
            $response = new WebResponse(true, [], 'Запись успешно удалена', [], 201);
        } catch (ModelNotFoundException $e) {
            $response = new WebResponse(false, null, 'Запись не найдена для удаления', [], 400);
            Log::error(__METHOD__ . var_export($e->getMessage(), true));
        } catch (QueryException $e) {
            $response = new WebResponse(false, null, 'Запись не удалена', [], 500);
            Log::error(__METHOD__ . var_export($e->getMessage(), true));
        } catch (Throwable $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 500);
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json($response->toArray(), $response->statusCode);
        }
    }
}
