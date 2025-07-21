<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'month_name'    => 'required|string',
            'month_to_pay'  => 'required|string',
            'month_to_date' => 'required|string',
            'bill'          => 'required|string',
            'pay_up_to'     => 'required|string',
        ]);

        $setting->update($request->only([
            'month_name',
            'month_to_pay',
            'month_to_date',
            'bill',
            'pay_up_to',
        ]));

        return redirect()->route('admin.settings.index')
            ->with('success', 'Настройка обновлена');
    }
}
