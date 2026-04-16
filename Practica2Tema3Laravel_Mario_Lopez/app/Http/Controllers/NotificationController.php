<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark notification as read then redirect to its post (or back).
     */
    public function read(Notification $notification)
    {
        abort_if(Auth::id() !== $notification->user_id, 403);

        $notification->update(['read' => true]);

        if ($notification->post_id) {
            return redirect("/vistaprevia/{$notification->post_id}");
        }

        return back();
    }

    /**
     * Mark every unread notification for the authenticated user as read.
     */
    public function markAllRead(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->notifications()->where('read', false)->update(['read' => true]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }
}
