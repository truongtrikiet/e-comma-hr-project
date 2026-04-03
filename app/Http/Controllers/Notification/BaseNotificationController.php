<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Support\Facades\Auth;
use App\Acl\Acl;
use App\Http\Controllers\Controller;

abstract class BaseNotificationController extends Controller
{
    protected function listNotifications(int $perPage = 10)
    {
        return Auth::user()
            ->notifications()
            ->latest()
            ->paginate($perPage);
    }

    protected function readNotification(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        if (!empty($notification->data['action_url'])) {
            return redirect($notification->data['action_url']);
        }

        return back();
    }

    protected function markAllRead()
    {
        Auth::user()
            ->unreadNotifications
            ->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    protected function clearAll()
    {
        $user = Auth::user();
        if (!$user) {
            return back();
        }

        $user->notifications()->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications cleared.');
    }
}
