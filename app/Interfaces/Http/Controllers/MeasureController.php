<?php

namespace App\Interfaces\Http\Controllers;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Response\WebResponse;
use App\Application\Services\Measure\MeasureServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class MeasureController extends Controller
{
    public MeasureServiceInterface $measureService;

    public function __construct(MeasureServiceInterface $measureService)
    {
        $this->measureService = $measureService;
    }

    public function index(): Response
    {
        try {
            $measures = $this->measureService->prepairDataForIndex();
            $response = new WebResponse(true, $measures, 'Успешно');
        } catch (NotFoundException $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], $e->getCode());
            Log::warning(__METHOD__ . var_export($response, true));
        } catch (Throwable $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 500);
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->view('measure.index.index', compact('response'), $response->statusCode);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
