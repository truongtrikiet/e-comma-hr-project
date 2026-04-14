<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Notification\BaseNotificationController;

class NotificationController extends BaseNotificationController
{
    public function index()
    {
        $notifications = $this->listNotifications();

        return view('hr.notification.index', compact('notifications'));
    }

    public function read(string $id)
    {
        return $this->readNotification($id);
    }

    public function markAllAsRead()
    {
        return $this->markAllRead();
    }
}
