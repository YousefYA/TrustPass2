<?php
<<<<<<< HEAD

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing page (default)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
});

/*
|--------------------------------------------------------------------------
| Vue SPA entry (login / register / vault)
|--------------------------------------------------------------------------
*/
Route::get('/{any}', function () {
    return view('app');
})->where('any', 'login|register|vault');
=======

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Landing page
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('landing'));

/*
|--------------------------------------------------------------------------
| Vue SPA entry
|--------------------------------------------------------------------------
*/
Route::get('/{any}', fn () => view('app'))
    ->where('any', 'login|register|vault');

/*
|--------------------------------------------------------------------------
| Session-based authentication (WEB)
|--------------------------------------------------------------------------
*/
Route::post('/login/verify', [LoginController::class, 'verify']);

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['status' => 'logged_out']);
});

Route::middleware('auth')->get('/me', fn () => auth()->user());
>>>>>>> 9d6c5fc (session is handled and corrected now)
