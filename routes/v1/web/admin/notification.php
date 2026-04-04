<?php

use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Acl\Acl;

Route::middleware(['auth', 'role:' . Acl::ROLE_SUPER_ADMIN . '|' . Acl::ROLE_ADMIN])->prefix('admin')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('admin.notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])
        ->name('admin.notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('admin.notifications.read-all');
    Route::post('/notifications/clear', [NotificationController::class, 'clearAll'])
        ->name('admin.notifications.clear-all');
});
