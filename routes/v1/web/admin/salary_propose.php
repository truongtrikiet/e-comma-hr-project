<?php

use App\Http\Controllers\Admin\SalaryProposeController;
use Illuminate\Support\Facades\Route;

Route::resource('salary-propose', SalaryProposeController::class)->names('admin.salary-propose');
Route::post('salary-propose/{salary_propose}/approve', [SalaryProposeController::class, 'approved'])->name('admin.salary-propose.approved');
