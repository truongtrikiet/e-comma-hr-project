<?php

use App\Http\Controllers\Staff\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('user', UserController::class)->names('staff.user');
Route::get('user/profile/{user}', [UserController::class, 'profile'])->name('staff.user.profile');
Route::post('user/update-avatar', [UserController::class, 'updateAvatar'])->name('staff.user.update_avatar');
