<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestLogController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::channel('telegram')->error('error level error');
        Log::channel('telegram')->critical('critical level error');
        Log::channel('telegram')->alert('alert level error');
        Log::channel('telegram')->emergency('emergency level error');

        return "Логи отправлены. Проверьте свои Telegram каналы.";
    }
}
