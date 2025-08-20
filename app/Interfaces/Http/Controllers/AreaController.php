<?php

namespace App\Interfaces\Http\Controllers;

use App\Infrastructure\Helpers\LocaleHelper;
use App\Interfaces\Http\Requests\Area\StoreAreaRequest;
use App\Interfaces\Http\Requests\Area\UpdateAreaRequest;
use App\Interfaces\Response\WebResponse;
use App\Application\Services\Area\AreaServiceInterface;
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
        } catch (Throwable $th) {
            $response = new WebResponse(
                false, 
                null, 
                $th->getMessage(),  
                is_null($th->getPrevious()) ? [] : $th->getPrevious()->getTrace(), 
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->view('area.index.index', compact('response'), $response->statusCode);
        }
    }

    public function create(): Response
    {
        try {
            $response = new WebResponse(true, [], 'Успешно');
        } catch (Throwable $th) {
            $response = new WebResponse(
                false, 
                null, 
                $th->getMessage(),  
                is_null($th->getPrevious()) ? [] : $th->getPrevious()->getTrace(), 
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->view('area.create.create', compact('response'), $response->statusCode);
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
                is_null($th->getPrevious()) ? [] : $th->getPrevious()->getTrace(), 
                $th->getCode()
            );
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
        } catch (Throwable $th) {
            $response = new WebResponse(
                false, 
                null, 
                $th->getMessage(),  
                is_null($th->getPrevious()) ? [] : $th->getPrevious()->getTrace(), 
                $th->getCode()
            );
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
        } catch (Throwable $th) {
            $response = new WebResponse(
                false, 
                null, 
                $th->getMessage(),  
                is_null($th->getPrevious()) ? [] : $th->getPrevious()->getTrace(), 
                $th->getCode()
            );
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
        } catch (Throwable $th) {
            $response = new WebResponse(
                false, 
                null, 
                $th->getMessage(),  
                is_null($th->getPrevious()) ? [] : $th->getPrevious()->getTrace(), 
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json($response->toArray(), $response->statusCode);
        }
    }
}
