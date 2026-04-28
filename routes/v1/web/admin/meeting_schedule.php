<?php

use App\Http\Controllers\Admin\MeetingScheduleController;
use Illuminate\Support\Facades\Route;

Route::resource('meeting-schedule', MeetingScheduleController::class)->names('admin.meeting-schedule');
