<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Helpers\AreaCalculator;
use App\Services\FeeCalculatorService;
use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ValueObjects\Owner;
use App\Domain\Apartment\ValueObjects\SerialNumber;

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

            return $query->get()->map(function ($apartment) {
                return Apartment::create(
                    new Owner($apartment->owner),
                    new SerialNumber((int) $apartment->serial_number)
                )->setRelation('details', $apartment->details)
                 ->setRelation('fees', $apartment->fees);
            });
        });

        return view('home', [
            'title' => 'ТСЖ Радуга',
            'apartments' => $apartments,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'owner' => 'required|string',
            'serial_number' => 'required|integer|min:1',
        ]);

        $apartment = Apartment::create(
            new Owner($data['owner']),
            new SerialNumber($data['serial_number'])
        );

        $apartment->save();

        return redirect()->route('apartments.index')->with('success', 'Apartment created successfully.');
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
