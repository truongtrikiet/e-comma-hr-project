<?php

use App\Http\Controllers\Admin\FurloughController;
use Illuminate\Support\Facades\Route;

Route::resource('furlough', FurloughController::class)->names('admin.furlough');
Route::put('furloughs/{furlough}/approved', [FurloughController::class, 'approved'])->name('admin.furlough.approved');
