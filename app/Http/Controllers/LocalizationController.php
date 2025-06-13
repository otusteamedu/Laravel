<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LocalizationController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        if (! in_array($request->locale, array_keys(config()->get('locale.available_locales')))) {
            abort(400);
        }

        $request->session()->put('locale', $request->locale);

        return redirect()->back();
    }
}
