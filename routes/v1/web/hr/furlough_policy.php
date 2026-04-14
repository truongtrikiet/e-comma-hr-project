<?php

use App\Http\Controllers\Hr\FurloughPolicyController;
use Illuminate\Support\Facades\Route;

Route::resource('furlough-policies', FurloughPolicyController::class)->names('hr.furlough-policies');
