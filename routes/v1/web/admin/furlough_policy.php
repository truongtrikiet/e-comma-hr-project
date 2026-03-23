<?php

use App\Http\Controllers\Admin\FurloughPolicyController;
use Illuminate\Support\Facades\Route;

Route::resource('furlough-policies', FurloughPolicyController::class)->names('admin.furlough-policies');
