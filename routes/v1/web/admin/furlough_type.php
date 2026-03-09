<?php

use App\Http\Controllers\Admin\FurloughTypeController;
use Illuminate\Support\Facades\Route;

Route::resource('furlough-type', FurloughTypeController::class)->names('admin.furlough-type');
