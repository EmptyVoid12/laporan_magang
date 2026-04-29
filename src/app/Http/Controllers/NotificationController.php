<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead(DatabaseNotification $notification): RedirectResponse
    {
        abort_unless(
            Auth::id() === (int) $notification->notifiable_id && $notification->notifiable_type === Auth::user()::class,
            403
        );

        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        return back();
    }

    public function markAllAsRead(): RedirectResponse
    {
        Auth::user()?->unreadNotifications->markAsRead();

        return back();
    }
}
