<?php

use App\Http\Controllers\Hr\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Acl\Acl;

Route::middleware(['auth', 'role:' . Acl::ROLE_HR])->prefix('hr')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('hr.notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])
        ->name('hr.notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('hr.notifications.read-all');
    Route::post('/notifications/clear', [NotificationController::class, 'clearAll'])
        ->name('hr.notifications.clear-all');
});
