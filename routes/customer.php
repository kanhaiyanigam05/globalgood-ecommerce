<?php

use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('')->as('')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'login'])->name('login');
        Route::post('login', [AuthController::class, 'authenticate'])->name('authenticate');
    });
    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
