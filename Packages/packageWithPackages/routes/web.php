<?php

use Illuminate\Support\Facades\Route;
use My\PackageWithPackages\Http\Controllers\StartPackageController;

Route::get('/test3', function () { echo 'GGGOOOOOO777!'; });

Route::prefix('package/')->group(function () {
    Route::get('test', [StartPackageController::class, 'packageWork'])->name('packageWork')
        ->middleware('packHeaders:Cache-Control=no-cache');
});

