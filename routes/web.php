<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Categories;
use App\Http\Controllers\Admin\Tasks;
use App\Http\Controllers\Admin\Users;
use App\Http\Controllers\Public\Tasks as PublicTasks;

Route::get('/', function () {
    return view('welcome');
});

// Публичные роуты для задач
Route::prefix('tasks')
    ->name('tasks.')
    ->middleware(['auth'])
    ->group(function () {
    Route::get('/', [PublicTasks\IndexController::class, 'index'])->name('index');
    Route::get('/create', [PublicTasks\CreateController::class, 'create'])->name('create');
    Route::post('/', [PublicTasks\CreateController::class, 'store'])->name('store');
    Route::get('/{task}', [PublicTasks\ShowController::class, 'show'])->name('show');
    Route::get('/{task}/edit', [PublicTasks\UpdateController::class, 'edit'])->name('edit');
    Route::put('/{task}', [PublicTasks\UpdateController::class, 'update'])->name('update');
    Route::delete('/{task}', [PublicTasks\DeleteController::class, 'destroy'])->name('destroy');
});

// Маршруты админ-панели
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'can:admin-access'])
    ->group(function () {
    // Дашборд
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Управление категориями
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [Categories\IndexController::class, 'index'])->name('index');
        Route::get('/create', [Categories\CreateController::class, 'create'])->name('create');
        Route::post('/', [Categories\CreateController::class, 'store'])->name('store');
        Route::get('/{category}', [Categories\ShowController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [Categories\UpdateController::class, 'edit'])->name('edit');
        Route::put('/{category}', [Categories\UpdateController::class, 'update'])->name('update');
        Route::delete('/{category}', [Categories\DestroyController::class, 'destroy'])->name('destroy');
    });

    // Управление задачами
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [Tasks\IndexController::class, 'index'])->name('index');
        Route::get('/create', [Tasks\CreateController::class, 'create'])->name('create');
        Route::post('/', [Tasks\CreateController::class, 'store'])->name('store');
        Route::get('/{task}', [Tasks\ShowController::class, 'show'])->name('show');
        Route::get('/{task}/edit', [Tasks\UpdateController::class, 'edit'])->name('edit');
        Route::put('/{task}', [Tasks\UpdateController::class, 'update'])->name('update');
        Route::delete('/{task}', [Tasks\DestroyController::class, 'destroy'])->name('destroy');
    });

    // Управление пользователями
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [Users\IndexController::class, 'index'])->name('index');
        Route::get('/create', [Users\CreateController::class, 'create'])->name('create');
        Route::post('/', [Users\CreateController::class, 'store'])->name('store');
        Route::get('/{user}', [Users\ShowController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [Users\UpdateController::class, 'edit'])->name('edit');
        Route::put('/{user}', [Users\UpdateController::class, 'update'])->name('update');
        Route::delete('/{user}', [Users\DestroyController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth'])->get('/dashboard', function () {
    // Здесь можно получить статистику задач пользователя
    $user = auth()->user();
    $tasksTotal = $user->tasks()->count();
    $tasksNew = $user->tasks()->where('status', 'new')->count();
    $tasksInProgress = $user->tasks()->where('status', 'in_progress')->count();
    $tasksDone = $user->tasks()->where('status', 'done')->count();
    return view('dashboard', compact('tasksTotal', 'tasksNew', 'tasksInProgress', 'tasksDone'));
})->name('dashboard');

require __DIR__.'/auth.php';
