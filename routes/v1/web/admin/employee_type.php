<?php

use App\Http\Controllers\Admin\EmployeeTypeController;
use Illuminate\Support\Facades\Route;

Route::resource('employee-type', EmployeeTypeController::class)->names('admin.employee-type');
