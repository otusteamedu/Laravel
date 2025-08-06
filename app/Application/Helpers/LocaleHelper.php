<?php

namespace App\Application\Helpers;

class LocaleHelper 
{
    public static function getLocale():string 
    {
        return config('app.locale');
    }
}