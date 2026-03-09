<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('user', UserController::class)->names('admin.user');
Route::get('user/profile/{user}', [UserController::class, 'profile'])->name('admin.user.profile');
Route::post('user/update-avatar', [UserController::class, 'updateAvatar'])->name('admin.user.update_avatar');
