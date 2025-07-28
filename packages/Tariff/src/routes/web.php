<?php

use Illuminate\Support\Facades\Route;
use Tariff\Http\Controllers\TariffController;

Route::get('/tariffs', [TariffController::class, 'index']);
