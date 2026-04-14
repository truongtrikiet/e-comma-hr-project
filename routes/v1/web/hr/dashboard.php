<?php

use App\Http\Controllers\Hr\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('hr.dashboard');
