<?php

use Illuminate\Support\Facades\Route;

Route::prefix('staff.')->group(function () {
    include 'staff/dashboard.php';
});
