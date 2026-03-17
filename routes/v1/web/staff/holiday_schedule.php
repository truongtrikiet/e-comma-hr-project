<?php

use App\Http\Controllers\Staff\HolidayScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('holiday-schedule/list', [HolidayScheduleController::class, 'list'])->name('staff.holiday-schedule.list');
Route::resource('holiday-schedule', HolidayScheduleController::class)->names('staff.holiday-schedule');
