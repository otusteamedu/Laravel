<?php

use Illuminate\Support\Facades\Route;
use Vagrant\Ascii\Controllers\AsciiController;

if (config("ascii.enabled", true)) {
    Route::group(
        [
            "prefix" => config("ascii.url_prefix", "ascii"),
            "as" => config("ascii.route_name_prefix", "ascii."),
            "middleware" => ["web"]
        ],
        function () {
            Route::get('/form', [AsciiController::class, 'form']);

            Route::post('/render', [AsciiController::class, 'render'])->name('render');
        }
    );
}