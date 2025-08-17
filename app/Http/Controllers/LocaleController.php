<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function show($locale)
    {
        // Обработка запроса и возврат ответа
        $message = trans('locale.welcome'); 
        return view('web.locale', ['locale'=>$locale,'message'=>$message]);
    }
}
