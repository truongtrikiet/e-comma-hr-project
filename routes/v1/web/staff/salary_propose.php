<?php

use App\Http\Controllers\Staff\SalaryProposeController;
use Illuminate\Support\Facades\Route;

Route::resource('salary-propose', SalaryProposeController::class)->names('staff.salary-propose');
