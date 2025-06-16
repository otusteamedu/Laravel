<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LocalizationController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->session()->put('locale', $request->locale);

        return redirect()->back();
    }
}
