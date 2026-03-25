<?php

use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {
    include 'admin/dashboard.php';
    include 'admin/user.php';
    include 'admin/department.php';
    include 'admin/school.php';
    include 'admin/role.php';
    include 'admin/subject.php';
    include 'admin/furlough_type.php';
    include 'admin/furlough.php';
    include 'admin/employee_type.php';
    include 'admin/holiday_schedule.php';
    include 'admin/furlough_policy_template.php';
    include 'admin/furlough_policy.php';
    include 'admin/notification.php';
});
