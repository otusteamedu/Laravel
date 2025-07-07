<?php

namespace App\TodoApp\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LocalizationController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $locale = $request->locale;

        if (in_array($locale, array_keys(config()->get('locale.available_locales')))) {
            $request->session()->put('locale', $locale);

            return redirect()->back();
        }

        return redirect()->back()->with('error',  __("Localization :locale not available yet", ['locale' => $locale]));
    }
}
