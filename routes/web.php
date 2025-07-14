<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\FallbackController;

Auth::routes();

Route::prefix('{locale}')
     ->where(['locale' => implode('|', config('app.supported_locales'))])
     ->middleware(['locale'])
     ->group(function () {

         Route::get('/', function () {
             return view('welcome', [
                 'services' => ['Услуга 1', 'Услуга 2', 'Услуга 3']
             ]);
         });

         Route::get('/home', HomeController::class)->middleware(['auth'])->name('home');
     });


Route::get('/test-package', function () {

    //dd(UserLogo::generate('Иванов Иван'));
    //dd(userlogo('Иванов Иван'));
    //dd(userlogo()->generate('Иванов Иван'));

    return '';
});

Route::get('/log', function () {
    //Log::debug('debug');
    Log::channel('telegram')->debug('debug level error');
    /* Log::channel('telegram')->info('info level error');
     Log::channel('telegram')->error('error level error');
     Log::channel('telegram')->critical('critical level error');
     Log::channel('telegram')->emergency('emergency level error');*/
    //Log::channel('telegram')->critical('critical level error');

    return ['status' => 'success'];
});

Route::get('/test', function (AuthController $authController) {
   // dump($authController->revoke('tYIX3C3aUH4tESEiEzco6HWVMkG9LDMrPcpEAVlwlOFys9ciu8HTohxBYUgx2WIe'));
    return ['status' => 'success'];
});

Route::fallback(FallbackController::class);
