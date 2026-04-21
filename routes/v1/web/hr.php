<?php

use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {
    include 'hr/dashboard.php';
    include 'hr/furlough.php';
    include 'hr/user.php';
    include 'hr/holiday_schedule.php';
    include 'hr/notification.php';
    include 'hr/contract.php';
    include 'hr/appendix_contract.php';
    include 'hr/contract_attribute.php';
    include 'hr/contract_type.php';
    include 'hr/furlough_policy.php';
    include 'hr/furlough_policy_template.php';
    include 'hr/editor_upload.php';
    include 'hr/salary.php';
    include 'hr/ai_profile.php';
    include 'hr/candidate_screening.php';
});
