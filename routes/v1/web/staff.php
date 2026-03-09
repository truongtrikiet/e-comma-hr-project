<?php

use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {
    include 'staff/dashboard.php';
    include 'staff/furlough.php';
    include 'staff/user.php';
});
