<?php

use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {
    include 'staff/dashboard.php';
    include 'staff/furlough.php';
    include 'staff/user.php';
    include 'staff/holiday_schedule.php';
    include 'staff/notification.php';
    include 'staff/contract.php';
    include 'staff/salary_propose.php';
    include 'staff/salary.php';
    include 'staff/meeting_schedule.php';
});
