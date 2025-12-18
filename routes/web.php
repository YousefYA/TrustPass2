<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\EmailOtpController;

Route::post('/otp/send', [EmailOtpController::class, 'send']);
Route::post('/otp/verify', [EmailOtpController::class, 'verify']);
Route::get('/', function () {
    // This uses the closure syntax from the first block
    return view('landing');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', 'login|register|vault');


/*
|--------------------------------------------------------------------------
| Session-based authentication (WEB)
|--------------------------------------------------------------------------
| These routes handle the authentication process.
*/

// POST route for verifying the login (e.g., OTP verification)
Route::post('/login/verify', [LoginController::class, 'verify']);

// POST route for logging out the user
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['status' => 'logged_out']);
});

// GET route to check the currently authenticated user
Route::middleware('auth')->get('/me', fn () => auth()->user());