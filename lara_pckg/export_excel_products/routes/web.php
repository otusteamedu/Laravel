<?php

use Illuminate\Support\Facades\Route;
use Ivan\ExportExcelProducts\Controllers\ExportController;


if(config('export_excel_products.enabled', true)) {
    Route::group(
        [
            'middleware' => ['web'],
            "prefix" => config('export_excel_products.route_prefix', "export")
        ], function () {
        Route::get("settings", [ExportController::class, 'form'])->name('export_excel_products.settings');
        Route::post("save", [ExportController::class, 'save'])->name('export_excel_products.save');
    });
}
