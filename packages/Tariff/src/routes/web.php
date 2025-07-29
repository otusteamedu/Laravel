<?php

use Illuminate\Support\Facades\Route;
use Tariff\Http\Controllers\TariffController;

Route::prefix('tariffs')->name('tariffs.')->group(function () {
    Route::get('/', [TariffController::class, 'index'])->name('index');                  // Список тарифов
    Route::get('/create', [TariffController::class, 'create'])->name('create');          // Форма создания
    Route::post('/', [TariffController::class, 'store'])->name('store');                 // Сохранить новый тариф

    Route::get('/{id}/edit', [TariffController::class, 'edit'])->name('edit');           // Форма редактирования
    Route::put('/{id}', [TariffController::class, 'update'])->name('update');            // Обновить тариф

    Route::get('/{id}/delete', [TariffController::class, 'confirmDelete'])->name('confirm_delete'); // Подтверждение удаления
    Route::delete('/{id}', [TariffController::class, 'destroy'])->name('destroy');       // Удалить тариф
});
