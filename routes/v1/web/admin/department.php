<?php

use App\Http\Controllers\Admin\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::resource('department', DepartmentController::class)->names('admin.department');
