<?php

use App\Http\Controllers\Admin\SchoolWorkingCalendarController;
use Illuminate\Support\Facades\Route;

Route::resource('school-working-calendar', SchoolWorkingCalendarController::class)->names('admin.school-working-calendar');
