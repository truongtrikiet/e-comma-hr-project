<?php

use App\Http\Controllers\Hr\MeetingScheduleController;
use Illuminate\Support\Facades\Route;

Route::resource('meeting-schedule', MeetingScheduleController::class)->names('hr.meeting-schedule');
