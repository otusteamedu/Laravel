<?php

use Illuminate\Support\Facades\Route;



// Главная страница
Route::get('/', function () {
    return view('home', ['title' => 'ТСЖ Радуга']);
})->name('index');

//Страница пользователя
Route::get('/user', function () {
    return view('user', [
        'title' => 'Личный кабинет',
        'showModals' => true
    ]);
})->name('user.profile');

//Страница регистрации
Route::get('/register', function () {
    return view('auth.register', [
        'title' => 'Регистрация',
        'showModals' => false
    ]);
})->name('register');

// Страница квартир (наследуется от apartment/base.blade.php)
Route::get('/apartments', function () {
    return view('apartments.index', [
        'title' => 'Квартиры',
        'apartments' => [] // Пустой массив для примера
    ]);
})->name('apartments.index');

// Страница тарифов
Route::get('/tariffs', function () {
    return view('tariffs', ['title' => 'Тарифы']);
})->name('tariffs.index');

// Страница входа (заглушка)
Route::get('/login', function () {
    return view('auth.login', ['title' => 'Вход']);
})->name('login');

// Выход (заглушка)
Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Admin\SettingController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('settings', SettingController::class)->only([
        'index', 'edit', 'update'
    ]);
    Route::resource('apartments', ApartmentController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);
});

