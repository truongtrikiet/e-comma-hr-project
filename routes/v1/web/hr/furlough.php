<?php

use App\Http\Controllers\Hr\FurloughController;
use Illuminate\Support\Facades\Route;

Route::resource('furlough', FurloughController::class)->names('hr.furlough');
Route::put('furloughs/{furlough}/approved', [FurloughController::class, 'approved'])->name('hr.furlough.approved');
