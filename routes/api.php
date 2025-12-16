<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login/init', [LoginController::class, 'init']);

Route::get('/health', fn () => response()->json([
    'status' => 'ok',
]));