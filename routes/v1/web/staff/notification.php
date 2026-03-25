<?php

use App\Http\Controllers\Staff\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Acl\Acl;

Route::middleware(['auth', 'role:' . Acl::ROLE_STAFF . '|' . Acl::ROLE_TEACHER])->prefix('staff')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('staff.notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])
        ->name('staff.notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('staff.notifications.readAll');
});
