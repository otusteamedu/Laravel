<?php

namespace Tariff\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tariff\Models\Tariff;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;

class TariffController extends Controller
{
    public function index(Request $request)
    {
        $tariffs = Tariff::all();

        if ($request->header('HX-Request')) {
            return view('tariff::tariff.partials.tbody', compact('tariffs'));
        }

        return view('tariff::tariff.index', [
            'tariffs' => $tariffs,
            'title' => 'Тарифы',
        ]);
    }

    public function create()
    {
        $errors = session()->get('errors', new ViewErrorBag());

        return view('tariff::tariff.edit', [
            'tariff' => new Tariff(),
            'title' => 'СОЗДАНИЕ НОВОГО ТАРИФА',
            'errors' => $errors,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'hot_water_odn' => 'nullable|numeric',
            'solid_waste' => 'nullable|numeric',
            'maintenance' => 'nullable|numeric',
            'cold_water' => 'nullable|numeric',
            'electricity' => 'nullable|numeric',
            'heating' => 'nullable|numeric',
            'cold_water_odn' => 'nullable|numeric',
            'electricity_odn' => 'nullable|numeric',
            'heating_rub' => 'nullable|numeric',
            'sewage' => 'nullable|numeric',
            'lift' => 'nullable|numeric',
            'hot_water' => 'nullable|numeric',
            'sewage_odn' => 'nullable|numeric',
            'capital_repair' => 'nullable|numeric',
            'multiplying_factor' => 'nullable|numeric',
        ]);

        $tariff = Tariff::create($data);

        return response('', 204)->header('HX-Trigger', json_encode([
            'tariffListChanged' => null,
            'showMessage' => "Тариф {$tariff->name} добавлен.",
        ]));
    }


    public function edit($id)
    {
        $tariff = Tariff::findOrFail($id);
        $errors = session()->get('errors', new ViewErrorBag());

        return view('tariff::tariff.edit', [
            'tariff' => $tariff,
            'title' => 'РЕДАКТИРОВАНИЕ ТАРИФА',
            'errors' => $errors,
        ]);
    }

    public function update(Request $request, $id)
    {
        $tariff = Tariff::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:200',
            'hot_water_odn' => 'nullable|numeric',
            'solid_waste' => 'nullable|numeric',
            'maintenance' => 'nullable|numeric',
            'cold_water' => 'nullable|numeric',
            'electricity' => 'nullable|numeric',
            'heating' => 'nullable|numeric',
            'cold_water_odn' => 'nullable|numeric',
            'electricity_odn' => 'nullable|numeric',
            'heating_rub' => 'nullable|numeric',
            'sewage' => 'nullable|numeric',
            'lift' => 'nullable|numeric',
            'hot_water' => 'nullable|numeric',
            'sewage_odn' => 'nullable|numeric',
            'capital_repair' => 'nullable|numeric',
            'multiplying_factor' => 'nullable|numeric',
        ]);

        $tariff->update($data);

        return response('', 204)->header('HX-Trigger', json_encode([
            'tariffListChanged' => null,
            'showMessage' => "Тариф {$tariff->name} изменен.",
        ]));
    }

    public function destroy($id)
    {
        $tariff = Tariff::findOrFail($id);
        $tariffName = $tariff->name;
        $tariff->delete();

        return redirect()->route('tariffs.index')
                        ->with('success', "Тариф {$tariffName} удалён.");
    }


    public function confirmDelete($id)
    {
        $tariff = Tariff::findOrFail($id);

        return view('tariff::tariff.confirm_delete', [
            'tariff' => $tariff,
            'tariff_name' => $tariff->name,
            'title' => 'ПОДТВЕРЖДЕНИЕ ДЕЙСТВИЯ',
        ]);
    }
}
