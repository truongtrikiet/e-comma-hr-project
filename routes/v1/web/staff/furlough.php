<?php

use App\Http\Controllers\Staff\FurloughController;
use Illuminate\Support\Facades\Route;

Route::resource('furlough', FurloughController::class)->names('staff.furlough');
