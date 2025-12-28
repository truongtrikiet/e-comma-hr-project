<?php

use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {
    include 'admin/dashboard.php';
    include 'admin/user.php';
});
