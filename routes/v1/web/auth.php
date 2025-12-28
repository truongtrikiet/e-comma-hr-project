<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.show-form');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('reset-password', [ResetPasswordController::class, 'showResetForm'])->name('reset.show-form');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');
});
