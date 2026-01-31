<?php

use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {
    include 'admin/dashboard.php';
    include 'admin/user.php';
    include 'admin/department.php';
    include 'admin/school.php';
    include 'admin/role.php';
    include 'admin/subject.php';
});
