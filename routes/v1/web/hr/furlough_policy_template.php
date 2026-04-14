<?php

use App\Http\Controllers\Hr\FurloughPolicyTemplateController;
use Illuminate\Support\Facades\Route;

Route::resource('furlough-policy-template', FurloughPolicyTemplateController::class)->names('hr.furlough-policy-template');
