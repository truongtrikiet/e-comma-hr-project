<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\SalaryController;

Route::resource('salary', SalaryController::class)->names('staff.salary');
