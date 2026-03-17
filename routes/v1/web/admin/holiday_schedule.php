<?php

use App\Http\Controllers\Admin\HolidayScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('holiday-schedule/list', [HolidayScheduleController::class, 'list'])->name('admin.holiday-schedule.list');
Route::resource('holiday-schedule', HolidayScheduleController::class)->names('admin.holiday-schedule');
