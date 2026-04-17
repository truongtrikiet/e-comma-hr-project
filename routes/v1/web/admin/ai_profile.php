<?php

use App\Http\Controllers\Admin\AIProfileController;
use Illuminate\Support\Facades\Route;

Route::post('ai-profile/{ai_profile}/test-api', [AIProfileController::class, 'testApi'])->name('admin.ai_profile.test_api');
Route::resource('ai-profile', AIProfileController::class)->names('admin.ai_profile');
