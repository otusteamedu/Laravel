<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Domain\Apartment\Repositories\ApartmentRepository;
use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ValueObjects\Owner;
use App\Domain\Apartment\ValueObjects\SerialNumber;
use App\Helpers\AreaCalculator;
use App\Services\FeeCalculatorService;

class ApartmentAreaController extends Controller
{
    private ApartmentRepository $repository;

    public function __construct(ApartmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $cacheKey = 'apartments_list_' . ($filter ?? 'all');

        $apartments = Cache::remember($cacheKey, 600, function () use ($filter) {
            return $this->repository->findAllWithFilter($filter);
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

        $apartment = new Apartment(
            new Owner($data['owner']),
            new SerialNumber($data['serial_number'])
        );

        $this->repository->save($apartment);

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

//для коммита