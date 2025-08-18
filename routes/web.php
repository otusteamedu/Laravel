<?

use Illuminate\Support\Facades\Route;
use Konstantin\Calc\Controllers\CalcController;

if (config('calc.enabled', true)) {
    Route::post('/'.config("calc.render"), [CalcController::class, config("calc.method")])->name(config("calc.render"));
    Route::get('/'.config("calc.method"), [CalcController::class, config("calc.method")])->name(config("calc.method"));
    Route::post('/'.config("calc.eval"), [CalcController::class, config("calc.eval")])->name(config("calc.eval"));
    
   
}

