<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view(
            'notifications.index',
            compact('notifications')
        );
    }

    public function read(
        Request $request,
        DatabaseNotification $notification
    ): RedirectResponse {
        abort_unless(
            $notification->notifiable_id === $request->user()->id,
            403
        );

        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        if ($url) {
            return redirect($url);
        }

        return back();
    }

    public function readAll(Request $request): RedirectResponse
    {
        $request->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }
}