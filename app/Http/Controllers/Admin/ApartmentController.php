<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Apartment\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::all();
        return view('admin.apartments.index', compact('apartments'));
    }

    public function create()
    {
        return view('admin.apartments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner' => 'required|string',
            'serial_number' => 'required|string',
        ]);

        Apartment::create($request->only(['owner', 'serial_number']));

        return redirect()->route('admin.apartments.index')
            ->with('success', 'Квартира добавлена');
    }

    public function edit(Apartment $apartment)
    {
        return view('admin.apartments.edit', compact('apartment'));
    }

    public function update(Request $request, Apartment $apartment)
    {
        $request->validate([
            'owner' => 'required|string',
            'serial_number' => 'required|string',
        ]);

        $apartment->update($request->only(['owner', 'serial_number']));

        return redirect()->route('admin.apartments.index')
            ->with('success', 'Квартира обновлена');
    }
}
