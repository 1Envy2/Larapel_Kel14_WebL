<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display notifications
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Show notification detail
     */
    public function show(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        // Mark as read if not already
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }
        
        return view('notifications.show', compact('notification'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        $notification->update(['read_at' => now()]);
        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->notifications()->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca');
    }

    /**
     * Delete notification
     */
    public function delete(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        $notification->delete();
        return back()->with('success', 'Notifikasi berhasil dihapus');
    }

    /**
     * Destroy notification (alias for delete)
     */
    public function destroy(Notification $notification)
    {
        return $this->delete($notification);
    }
}
