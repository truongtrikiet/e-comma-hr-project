<?php

use App\Http\Controllers\Hr\FurloughController;
use Illuminate\Support\Facades\Route;

Route::resource('furlough', FurloughController::class)->names('hr.furlough');
