<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('user', UserController::class)->names('admin.user');
Route::post('user/update-avatar', [UserController::class, 'updateAvatar'])->name('admin.user.update_avatar');
