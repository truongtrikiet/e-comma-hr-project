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
    include 'admin/school_working_calendar.php';
    include 'admin/appendix_contract.php';
    include 'admin/contract_attribute.php';
    include 'admin/contract.php';
    include 'admin/contract_type.php';
    include 'admin/editor_upload.php';
    include 'admin/salary.php';
    include 'admin/ai_profile.php';
    include 'admin/candidate_screening.php';
});
