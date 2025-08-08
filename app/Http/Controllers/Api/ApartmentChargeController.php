<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApartmentCharge;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApartmentChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $charges = ApartmentCharge::all();
        return response()->json($charges);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'money_deposited' => 'required|numeric',
            'fine' => 'required|numeric',
            'recalculation_electricity' => 'required|numeric',
            'recalculation_heating_rub' => 'required|numeric',
            'recalculation_hot_water' => 'required|numeric',
            'recalculation_cold_water' => 'required|numeric',
            'recalculation_sewage' => 'required|numeric',
            'recalculation_solid_waste' => 'required|numeric',
            'recalculation_maintenance' => 'required|numeric',
            'balance_start' => 'required|numeric',
            'apartment_id' => 'required|exists:apartments,id',
        ]);

        $charge = ApartmentCharge::create($validated);
        return response()->json($charge, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ApartmentCharge $apartmentCharge): JsonResponse
    {
        return response()->json($apartmentCharge);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApartmentCharge $apartmentCharge): JsonResponse
    {
        $validated = $request->validate([
            'money_deposited' => 'sometimes|numeric',
            'fine' => 'sometimes|numeric',
            'recalculation_electricity' => 'sometimes|numeric',
            'recalculation_heating_rub' => 'sometimes|numeric',
            'recalculation_hot_water' => 'sometimes|numeric',
            'recalculation_cold_water' => 'sometimes|numeric',
            'recalculation_sewage' => 'sometimes|numeric',
            'recalculation_solid_waste' => 'sometimes|numeric',
            'recalculation_maintenance' => 'sometimes|numeric',
            'balance_start' => 'sometimes|numeric',
            'apartment_id' => 'sometimes|exists:apartments,id',
        ]);

        $apartmentCharge->update($validated);
        return response()->json($apartmentCharge);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApartmentCharge $apartmentCharge): JsonResponse
    {
        $apartmentCharge->delete();
        return response()->json(null, 204);
    }
}
