<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;  
use App\Helpers\AreaCalculator;
use App\Models\Apartment;
use App\Services\FeeCalculatorService;

class ApartmentAreaController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $cacheKey = 'apartments_list_' . ($filter ?? 'all');

        $apartments = Cache::remember($cacheKey, 600, function () use ($filter) {
            $query = Apartment::with(['details', 'fees']);

            if ($filter === 'balance_end_gt_6000') {
                $query->whereHas('fees', function ($q) {
                    $q->where('balance_end', '>', 6000);
                });
            }

            return $query->get();
        });

        return view('home', [
            'title' => 'ТСЖ Радуга',
            'apartments' => $apartments,
        ]);
    }

    public function calculate(Request $request)
    {
        $data = $request->validate([
            'livedQt' => 'required|integer|min:0',
            'totalArea' => 'required|numeric|min:0',
        ]);

        list($areaByNorm, $areaOverNorm) = AreaCalculator::calculateArea($data['livedQt'], $data['totalArea']);

        return response()->json([
            'areaByNorm' => $areaByNorm,
            'areaOverNorm' => $areaOverNorm,
        ]);
    }

    public function calculateFees(FeeCalculatorService $service)
    {
        $service->calculate();

        return response()->json(['message' => 'Fees calculated successfully']);
    }
}
