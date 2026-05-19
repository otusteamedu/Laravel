<?php

use App\Http\Controllers\PostController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::apiResource('posts', PostController::class)->middleware('auth');

Route::get('/log', function (Request $request) {
    $amount = '200.00';
    $context = ['req' => $request, 'amount' => $amount];
    Log::info('Log message', $context);
    Log::debug('Amount', compact('amount'));

    return "ok";
});

Route::get('/collect', function (Request $request) {
    $users = User::all();

    dump($users
        ->filter(fn($user) => $user->is_admin)
        ->map(fn($user) => $user->name));

    dump($users->filter->is_admin->map->name);

    return "ok";
});

Route::get('/lc', function () {
    $users = User::cursor();

    $admins = $users->filter->is_admin;

    foreach ($admins as $admin) {
        echo '' . $admin->name . '<br>';
    }

    return 'ok';
});

Route::get('/create-html', function () {
    Storage::disk('public')->put('test.html', '<h1>file</h1>');

    return 'ok';
});

Route::get('/private-link', function () {
    Storage::put('private.html', '<h1>private</h1>');

    $url = Storage::temporaryUrl('private.html', now()->addMinutes(5));

    return $url;
});

Route::post('/upload-file', function () {
    $file = request()->file('image');
    $name = $file->getClientOriginalPath();
    $fileName = $file->storeAs('images', $name, 'public');

    return Storage::disk('local')->temporaryUrl($fileName, now()->addMinutes(5));

})->name('upload-file');

require __DIR__ . '/settings.php';
