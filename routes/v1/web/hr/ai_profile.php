<?php

use App\Http\Controllers\Hr\AIProfileController;
use Illuminate\Support\Facades\Route;

Route::post('ai-profile/{ai_profile}/test-api', [AIProfileController::class, 'testApi'])->name('hr.ai_profile.test_api');
Route::resource('ai-profile', AIProfileController::class)->names('hr.ai_profile');
