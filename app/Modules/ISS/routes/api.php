<?php

use App\Modules\ISS\src\Http\Controllers\Api\v1\UserDataController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => config('iss.ISS_ROUTE_PREFIX').'/api/v1',
        'name' => 'v1',
        'middleware' => ['api', 'auth:jwt'],
        ],
    function () {
        Route::apiResource('/issUser', UserDataController::class);
    }
);

