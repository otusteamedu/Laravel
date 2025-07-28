<?php

use App\Http\Controllers\Api\v1\CarsController;
use App\Http\Controllers\Api\v1\OauthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/redirect', function(Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => '01983387-420b-7041-9266-9cce8fad9f5d', // Replace with valid client id
        'redirect_uri' => 'http://localhost/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
        // 'prompt' => '', // "none", "consent", or "login"
    ]);

    return redirect('http://localhost/oauth/authorize?' . $query);
});

Route::get('/callback', function(Request $request) {
    $state = $request->session()->pull('state');

//    dd($request->state, $state);

    throw_unless(
        strlen($state) > 0 && $state === $request->state,
        InvalidArgumentException::class,
        'Invalid state value.'
    );

    $response = Http::asForm()->post('http://nginx/oauth/token', [
        'grant_type' => 'authorization_code',
        'client_id' => '01983387-420b-7041-9266-9cce8fad9f5d', // Replace with valid client id
        'client_secret' => 'jwVyy3rszPKtNipa6JTqmNpgIbgx89PlFtB910jT', // Replace with client secret
        'redirect_uri' => 'http://localhost/callback',
        'code' => $request->code
    ]);

    return $response->json();
});
