<?php

use App\Http\Controllers\Hr\SalaryProposeController;
use Illuminate\Support\Facades\Route;

Route::resource('salary-propose', SalaryProposeController::class)->names('hr.salary-propose');
Route::post('salary-propose/{salary_propose}/approve', [SalaryProposeController::class, 'approved'])->name('hr.salary-propose.approved');
