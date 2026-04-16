<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hr\SalaryController;

Route::resource('salary', SalaryController::class)->names('hr.salary');
