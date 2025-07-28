<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StoreCarRequest;
use App\Http\Requests\Api\v1\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $cars = Car::all();

        return new JsonResponse($cars);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request): CarResource
    {
        $input = $request->validated();
        $car = Car::query()->create($input);

        return new CarResource($car);
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car): CarResource
    {
        return new CarResource($car);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarRequest $request, Car $car): CarResource
    {
        $input = $request->validated();

        $car->fill($input);
        $car->save();

        return new CarResource($car);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car): JsonResponse
    {
        $car->delete();

        return new JsonResponse([], 204);
    }

    public function testScope(): JsonResponse
    {
        return new JsonResponse(['scopes' => 'working']);
    }
}
