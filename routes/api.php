<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailOtpController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::middleware('web')->group(function () {
    
    Route::post('/otp/send', [EmailOtpController::class, 'send']);
    Route::post('/otp/verify', [EmailOtpController::class, 'verify']);
    
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login/init', [LoginController::class, 'init']);

});