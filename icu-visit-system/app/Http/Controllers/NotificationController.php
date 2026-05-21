<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        abort_if($notification->user_id !== Auth::id(), 403);
        $notification->markAsRead();
        return back();
    }

    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(Notification $notification)
    {
        abort_if($notification->user_id !== Auth::id(), 403);
        $notification->delete();
        return back();
    }

    public function fetchUnread()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->latest()->take(10)->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'title'      => $n->title,
                'message'    => $n->message,
                'type'       => $n->type,
                'action_url' => $n->action_url,
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        $count = Notification::where('user_id', Auth::id())->whereNull('read_at')->count();

        return response()->json([
            'notifications' => $notifications,
            'count'         => $count,
        ]);
    }
}
