<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SalaryController;

Route::resource('salary', SalaryController::class)->names('admin.salary');
