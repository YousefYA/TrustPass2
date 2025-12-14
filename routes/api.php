<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

Route::post('/register', [RegisterController::class, 'store']);

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'TrustPass API'
    ]);
});