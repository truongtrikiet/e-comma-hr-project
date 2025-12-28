<?php

use App\Http\Controllers\Staff\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('staff.dashboard');
