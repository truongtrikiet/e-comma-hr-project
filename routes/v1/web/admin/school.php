<?php

use App\Http\Controllers\Admin\SchoolController;
use Illuminate\Support\Facades\Route;

Route::resource('school', SchoolController::class)->names('admin.school');
