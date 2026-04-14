<?php

use App\Http\Controllers\Hr\HolidayScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('holiday-schedule/list', [HolidayScheduleController::class, 'list'])->name('hr.holiday-schedule.list');
Route::resource('holiday-schedule', HolidayScheduleController::class)->names('hr.holiday-schedule');
