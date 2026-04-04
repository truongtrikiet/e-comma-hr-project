<?php

use App\Http\Controllers\Admin\FurloughPolicyTemplateController;
use Illuminate\Support\Facades\Route;

Route::resource('furlough-policy-template', FurloughPolicyTemplateController::class)->names('admin.furlough-policy-template');
