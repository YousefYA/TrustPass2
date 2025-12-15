<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::post('/login/init', [LoginController::class, 'init']);
Route::post('/login/verify', [LoginController::class, 'verify']);
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'TrustPass API'
    ]);
});