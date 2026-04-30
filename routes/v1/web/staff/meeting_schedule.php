<?php

use App\Http\Controllers\Staff\MeetingScheduleController;
use Illuminate\Support\Facades\Route;

Route::resource('meeting-schedule', MeetingScheduleController::class)->names('staff.meeting-schedule');
