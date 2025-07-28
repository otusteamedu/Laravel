<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ApartmentAreaController;


//временно для проверки memcached
Route::get('/test-session', function (Illuminate\Http\Request $request) {
    $request->session()->put('check_memcached', 'работает');

    return 'Сессия записана: ' . $request->session()->get('check_memcached');
});

// Главная страница
Route::get('/', [ApartmentAreaController::class, 'index'])->name('index');

// Страница тарифов
Route::get('/tariffs', function () {
    return view('tariffs', ['title' => 'Тарифы']);
})->name('tariffs.index');

// Страница квартир
Route::get('/apartments', function () {
    return view('apartments.index', [
        'title' => 'Квартиры',
        'apartments' => []
    ]);
})->name('apartments.index');

// Страница dashboard с локалью в URL
Route::get('/{locale}/dashboard', function ($locale) {
    // Устанавливаем локаль вручную
    if (in_array($locale, ['ru', 'en'])) {
        app()->setLocale($locale);
    } else {
        abort(404);
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard.locale');

// Обычный dashboard без локали — редирект на /ru/dashboard
Route::get('/dashboard', function () {
    return redirect('/ru/dashboard');
})->middleware(['auth'])->name('dashboard'); 

// Маршруты профиля (Breeze их ожидает)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Админская зона
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('settings', SettingController::class)->only([
        'index', 'edit', 'update'
    ]);
    Route::resource('apartments', ApartmentController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);
});

// Auth-маршруты Breeze (login, register, logout, reset password и т.д.)
require __DIR__.'/auth.php';


Route::get('/fail', function () {
    throw new \Exception("Test error to Telegram");
});

Route::post('/apartment/calculate-area', [ApartmentAreaController::class, 'calculate']);
Route::post('/apartment/calculate-fees', [ApartmentAreaController::class, 'calculateFees']);
Route::get('/calculate-fees', [ApartmentAreaController::class, 'calculateFees'])->name('calculate_fees');