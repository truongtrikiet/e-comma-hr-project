<?php

use App\Http\Controllers\Hr\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('user', UserController::class)->names('hr.user');
Route::get('user/profile/{user}', [UserController::class, 'profile'])->name('hr.user.profile');
Route::post('user/update-avatar', [UserController::class, 'updateAvatar'])->name('hr.user.update_avatar');
