<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    public function markAsRead($notificationId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Find the notification for this user
        $notification = $user->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();

        // Store redirect URL before deleting
        $destinationUrl = $notification->data['url'] ?? url()->previous();

        // Mark as read (optional now)
        $notification->markAsRead();

        // Delete immediately
        $notification->delete();

        return redirect($destinationUrl);
    }

    public function markAllAsRead()
    {
        /** @var User $user */
        $user = Auth::user();

        // Delete all unread notifications instantly
        $user->unreadNotifications()->delete();

        return back()->with('status', 'All notifications have been cleared.');
    }
}