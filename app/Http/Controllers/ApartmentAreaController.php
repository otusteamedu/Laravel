<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AreaCalculator;
use App\Models\Apartment;
use App\Models\ApartmentCharge;
use App\Models\ApartmentCounter;
use App\Models\ApartmentDetail;
use App\Models\ApartmentFee;
use App\Models\Tariff;
use App\Services\FeeCalculatorService;


class ApartmentAreaController extends Controller
{

    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $query = Apartment::with(['details', 'fees']);

        if ($filter === 'balance_end_gt_6000') {
            $query->whereHas('fees', function ($q) {
                $q->where('balance_end', '>', 6000);
            });
        }

        $apartments = $query->get();

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
