<?php

use Illuminate\Support\Facades\Route;
use Vagrant\Ascii\Controllers\AsciiController;

if (config('ascii.enabled', true)) {
    Route::group(["prefix" => config("ascii.route_prefix", "ascii")], function () {
        Route::get("/form", [AsciiController::class, 'form']);
        Route::post("/update", [AsciiController::class, 'render'])->name("ascii.render");
    });
}